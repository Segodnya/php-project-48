<?php

namespace Hexlet\Code\Formatters\Plain;

/**
 * @param array<int, array<string, mixed>> $diff
 */
function formatPlain(array $diff): string
{
    return implode("\n", formatPlainItems($diff));
}

/**
 * @param array<int, array<string, mixed>> $items
 * @param string $path
 * @return array<int, string>
 */
function formatPlainItems(array $items, string $path = ''): array
{
    $result = [];

    foreach ($items as $item) {
        $key = is_scalar($item['key'])
            ? (string) $item['key']
            : throw new \InvalidArgumentException('Key must be scalar');

        $newPath = $path ? "{$path}.{$key}" : $key;

        switch ($item['type']) {
            case 'add':
                $value = formatValue($item['value']);
                $result[] = "Property '{$newPath}' was added with value: {$value}";
                break;
            case 'deleted':
                $result[] = "Property '{$newPath}' was removed";
                break;
            case 'changed':
                $oldValue = formatValue($item['oldValue']);
                $newValue = formatValue($item['newValue']);
                $result[] = "Property '{$newPath}' was updated. From {$oldValue} to {$newValue}";
                break;
            case 'nested':
                if (is_array($item['children'])) {
                    $result = array_merge($result, formatPlainItems($item['children'], $newPath));
                }
                break;
        }
    }

    return $result;
}

/**
 * @param mixed $value
 */
function formatValue($value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_string($value)) {
        return "'{$value}'";
    }

    if (is_array($value)) {
        return '[complex value]';
    }

    if (is_scalar($value)) {
        return (string) $value;
    }

    throw new \InvalidArgumentException('Unexpected value type');
}
