<?php

namespace Hexlet\Code\Formatters\Stylish;

/**
 * @param array<int, array<string, mixed>> $diff
 */
function formatStylish(array $diff): string
{
    return "{\n" . formatStylishItems($diff, 1) . "}";
}

/**
 * @param array<string, mixed> $item
 */
function formatStylishItem(array $item, int $depth): string
{
    $indent = str_repeat('    ', $depth - 1);
    $key = array_key_exists('key', $item) && is_scalar($item['key']) ? (string) $item['key'] : '';
    $type = array_key_exists('type', $item) && is_string($item['type']) ? $item['type'] : '';

    switch ($type) {
        case 'unchanged':
            $value = formatValue($item['value'], $depth);
            return "{$indent}    {$key}: {$value}\n";

        case 'deleted':
            $value = formatValue($item['value'], $depth);
            return "{$indent}  - {$key}: {$value}\n";

        case 'add':
            $value = formatValue($item['value'], $depth);
            return "{$indent}  + {$key}: {$value}\n";

        case 'changed':
            $oldValue = formatValue($item['oldValue'], $depth);
            $newValue = formatValue($item['newValue'], $depth);
            return "{$indent}  - {$key}: {$oldValue}\n{$indent}  + {$key}: {$newValue}\n";

        case 'nested':
            $children = isset($item['children']) && is_array($item['children']) ? $item['children'] : [];
            $nestedValue = "{\n" . formatStylishItems($children, $depth + 1) . "{$indent}    }";
            return "{$indent}    {$key}: {$nestedValue}\n";

        default:
            throw new \Exception("Unknown diff type: {$type}");
    }
}

/**
 * @param array<int, array<string, mixed>> $items
 */
function formatStylishItems(array $items, int $depth): string
{
    $result = '';

    foreach ($items as $item) {
        $result .= formatStylishItem($item, $depth);
    }

    return $result;
}

/**
 * @param mixed $value
 */
function formatValue($value, int $depth): string
{
    if (is_array($value)) {
        $indent = str_repeat('    ', $depth);
        $result = "{\n";

        foreach ($value as $k => $v) {
            $formattedK = is_string($k) ? $k : json_encode($k);
            $formattedV = formatValue($v, $depth + 1);
            $result .= "{$indent}    {$formattedK}: {$formattedV}\n";
        }

        $result .= "{$indent}}";

        return $result;
    }

    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if ($value === '') {
        return '';
    }

    // Handle non-scalar values
    if (!is_scalar($value)) {
        $encoded = json_encode($value);

        return $encoded !== false ? $encoded : '[Complex Value]';
    }

    return (string) $value;
}
