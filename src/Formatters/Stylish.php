<?php

namespace Differ\Formatters\Stylish;

/**
 * @param array<int, array<string, mixed>> $diffTree
 */
function formatStylish(array $diffTree): string
{
    $result = makeStylish($diffTree);

    return "{\n$result\n}";
}

/**
 * @param array<int, array<string, mixed>> $diffTree
 */
function makeStylish(array $diffTree, int $depth = 1): string
{
    $result = array_map(
        function (array $node) use ($depth): string {
            $type = array_key_exists('type', $node) ? toString($node['type']) : '';
            $key = array_key_exists('key', $node) ? toString($node['key']) : '';

            $indent = getIndent($depth);
            $smallIndent = getSmallIndent($depth);

            switch ($type) {
                case 'nested':
                    $children = $node['children'] ?? [];
                    assert(is_array($children));
                    return "{$indent}{$key}: {\n" . makeStylish($children, $depth + 1) . "\n{$indent}}";

                case 'unchanged':
                    $value = stylishNodeValue($node['oldValue'] ?? null, $depth);
                    return "{$indent}{$key}: {$value}";

                case 'changed':
                    $oldValue = stylishNodeValue($node['oldValue'] ?? null, $depth);
                    $newValue = stylishNodeValue($node['newValue'] ?? null, $depth);
                    return "{$smallIndent}- {$key}: {$oldValue}\n"
                        . "{$smallIndent}+ {$key}: {$newValue}";

                case 'added':
                    $value = stylishNodeValue($node['newValue'] ?? null, $depth);
                    return "{$smallIndent}+ {$key}: {$value}";

                case 'deleted':
                    $value = stylishNodeValue($node['oldValue'] ?? null, $depth);
                    return "{$smallIndent}- {$key}: {$value}";

                default:
                    throw new \Exception("Unknown node type \"{$type}\".");
            }
        },
        $diffTree
    );

    return implode("\n", $result);
}

function stylishNodeValue(mixed $value, int $depth): string
{
    if (!is_object($value)) {
        return toString($value);
    }

    $keys = array_keys(get_object_vars($value));

    $result = array_map(
        function (string $key) use ($value, $depth): string {
            $indent = getIndent($depth + 1);
            return "{$indent}{$key}: " . stylishNodeValue($value->$key, $depth + 1);
        },
        $keys
    );

    $endIndent = getIndent($depth);

    return "{\n" . implode("\n", $result) . "\n{$endIndent}}";
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
        return $value;
    }

    if (is_scalar($value)) {
        return (string) $value;
    }

    $encoded = json_encode($value);
    return $encoded !== false ? $encoded : '';
}

function getSmallIndent(int $depth): string
{
    return getIndent($depth, 2);
}

function getIndent(int $depth = 1, int $shift = 0): string
{
    $baseIndentSize = 4;

    return str_repeat(' ', $baseIndentSize * $depth - $shift);
}
