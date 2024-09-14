<?php

namespace Hexlet\Code\Formatters;

/**
 * Formats the diff array into a string representation.
 *
 * @param array<int, array<string, mixed>> $diff The diff array to format
 * @param string $format The format to use (default: 'stylish')
 * @return string The formatted diff string
 * @throws \Exception If an unsupported format is specified
 */
function format(array $diff, string $format = 'stylish'): string
{
    switch ($format) {
        case 'stylish':
            return formatStylish($diff);

        default:
            throw new \Exception("Unsupported format: $format");
    }
}

/**
 * Formats the diff array in the 'stylish' format.
 *
 * @param array<int, array<string, mixed>> $diff The diff array to format
 * @return string The formatted diff string
 */
function formatStylish(array $diff): string
{
    return "{\n" . formatStylishItems($diff, 1) . "}\n";
}

/**
 * Formats a single item in the diff array for the 'stylish' format.
 *
 * @param array<string, mixed> $item The item to format
 * @param int $depth The current depth in the diff tree
 * @return string The formatted item string
 */
function formatStylishItem(array $item, int $depth): string
{
    $indent = str_repeat('    ', $depth - 1);
    $key = is_scalar($item['key']) ? (string) $item['key'] : throw new \InvalidArgumentException('Key must be scalar');
    $type = is_string($item['type']) ? $item['type'] : throw new \InvalidArgumentException('Type must be string');

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
            $result = '';

            if ($oldValue === '') {
                $result .= "{$indent}  - {$key}:\n";
            } else {
                $result .= "{$indent}  - {$key}: {$oldValue}\n";
            }

            $result .= "{$indent}  + {$key}: {$newValue}\n";

            return $result;

        case 'nested':
            if (!is_array($item['children'])) {
                throw new \Exception("Expected array for nested children");
            }

            $nestedValue = "{\n" . formatStylishItems($item['children'], $depth + 1) . "{$indent}    }";

            return "{$indent}    {$key}: {$nestedValue}\n";

        default:
            throw new \Exception("Unknown diff type: {$type}");
    }
}

/**
 * Formats a value for display in the 'stylish' format.
 *
 * @param mixed $value The value to format
 * @param int $depth The current depth in the diff tree
 * @return string The formatted value string
 */
function formatValue(mixed $value, int $depth): string
{
    if (is_array($value)) {
        $indent = str_repeat('    ', $depth);
        $result = "{\n";

        foreach ($value as $k => $v) {
            if (!is_scalar($k)) {
                throw new \InvalidArgumentException('Array key must be scalar');
            }

            $formattedK = (string) $k;
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

    if (!is_scalar($value)) {
        throw new \InvalidArgumentException('Value must be scalar');
    }

    return (string) $value;
}

/**
 * Formats an array of items in the 'stylish' format.
 *
 * @param array<int, array<string, mixed>> $items The items to format
 * @param int $depth The current depth in the diff tree
 * @return string The formatted items string
 */
function formatStylishItems(array $items, int $depth): string
{
    $result = '';

    foreach ($items as $item) {
        $result .= formatStylishItem($item, $depth);
    }

    return $result;
}
