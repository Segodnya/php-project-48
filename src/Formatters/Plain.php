<?php

namespace Differ\Formatters\Plain;

use function Functional\flat_map;

/**
 * @param array<mixed> $diffTree
 * @return string
 */
function formatPlain(array $diffTree): string
{
    $result = array_filter(makePlain($diffTree));

    return implode("\n", $result);
}

/**
 * @param array<mixed> $diffTree
 * @param string $parentKey
 * @return array<string>
 */
function makePlain(array $diffTree, string $parentKey = ''): array
{
    return flat_map(
        $diffTree,
        function ($node) use ($parentKey) {
            $type = $node['type'] ?? null;
            $key = $node['key'] ?? null;

            switch ($type) {
                case 'nested':
                    return makePlain($node['children'], "{$parentKey}{$key}.");
                case 'unchanged':
                    return '';
                case 'changed':
                    $oldValue = toString($node['oldValue']);
                    $newValue = toString($node['newValue']);
                    return "Property '{$parentKey}{$key}' was updated. From $oldValue to $newValue";
                case 'added':
                    $value = toString($node['newValue']);
                    return "Property '{$parentKey}{$key}' was added with value: $value";
                case 'deleted':
                    return "Property '{$parentKey}{$key}' was removed";
                default:
                    throw new \Exception("Unknown node type \"$type\".");
            }
        }
    );
}

function toString(mixed $value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_string($value)) {
        return "'$value'";
    }

    if (is_numeric($value)) {
        return (string) $value;
    }

    return '[complex value]';
}
