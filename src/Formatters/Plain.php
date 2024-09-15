<?php

namespace Hexlet\Code\Formatters\Plain;

/**
 * @param array<int, array<string, mixed>> $diff
 */
function formatPlain(array $diff): string
{
    return rtrim(implode("\n", formatPlainItems($diff)));
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
        if (!is_array($item) || !isset($item['key']) || !isset($item['type'])) {
            continue;
        }

        $key = is_scalar($item['key']) ? (string) $item['key'] : '[complex value]';
        $newPath = $path ? "{$path}.{$key}" : $key;

        switch ($item['type']) {
            case 'add':
                $value = formatValue($item['value'] ?? null);
                $result[] = "Property '{$newPath}' was added with value: {$value}";
                break;

            case 'deleted':
                $result[] = "Property '{$newPath}' was removed";
                break;

            case 'changed':
                $oldValue = formatValue($item['oldValue'] ?? null);
                $newValue = formatValue($item['newValue'] ?? null);
                $result[] = "Property '{$newPath}' was updated. From {$oldValue} to {$newValue}";
                break;

            case 'nested':
                if (isset($item['children']) && is_array($item['children'])) {
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

    return is_scalar($value) ? (string) $value : '[complex value]';
}
