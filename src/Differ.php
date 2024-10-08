<?php

namespace Differ\Differ;

use function Functional\sort;
use function Differ\Formatters\format;
use function Differ\Parsers\getFileContent;
use function Differ\Parsers\getFileExtension;
use function Differ\Parsers\parse;

/**
 * @typedef array{key: string, type: string, children?: array, oldValue?: mixed, newValue?: mixed} DiffNode
 */

function genDiff(string $pathToFirstFile, string $pathToSecondFile, string $formatType = 'stylish'): string
{
    $structure1 = parse(getFileExtension($pathToFirstFile), getFileContent($pathToFirstFile));
    $structure2 = parse(getFileExtension($pathToSecondFile), getFileContent($pathToSecondFile));
    $diffTree = getDiffTree($structure1, $structure2);

    return format($diffTree, $formatType);
}

/**
 * @param object $structure1
 * @param object $structure2
 * @return array<int, array<string, mixed>>
 */
function getDiffTree(object $structure1, object $structure2): array
{
    $keys = array_keys(array_merge((array) $structure1, (array) $structure2));
    $sortedKeys = sort($keys, fn($a, $b) => $a <=> $b);

    return array_map(
        function ($key) use ($structure1, $structure2) {
            $oldValue = $structure1->$key ?? null;
            $newValue = $structure2->$key ?? null;

            if (is_object($oldValue) && is_object($newValue)) {
                return [
                    'key' => $key,
                    'type' => 'nested',
                    'children' => getDiffTree($structure1->$key, $structure2->$key),
                ];
            }

            if (!property_exists($structure2, $key)) {
                $type = 'deleted';
            } elseif (!property_exists($structure1, $key)) {
                $type = 'added';
            } elseif ($oldValue !== $newValue) {
                $type = 'changed';
            } else {
                $type = 'unchanged';
            }

            return [
                'key' => $key,
                'type' => $type,
                'oldValue' => $oldValue,
                'newValue' => $newValue
            ];
        },
        $sortedKeys
    );
}
