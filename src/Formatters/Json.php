<?php

namespace Hexlet\Code\Formatters\Json;

/**
 * Formats the diff array into a JSON string representation.
 *
 * @param array<int, array<string, mixed>> $diff The diff array to format
 * @return string The formatted JSON string
 */
function formatJson(array $diff): string
{
    $result = formatJsonRecursive($diff);
    return json_encode($result, JSON_PRETTY_PRINT) ?: '{}';
}

/**
 * Recursively formats the diff array into a nested array suitable for JSON encoding.
 *
 * @param array<int, array<string, mixed>> $diff The diff array to format
 * @return array<int|string, mixed> The formatted array
 */
function formatJsonRecursive(array $diff): array
{
    $result = [];

    foreach ($diff as $item) {
        if (!is_array($item) || !isset($item['key'], $item['type'])) {
            continue;
        }

        $key = $item['key'];

        switch ($item['type']) {
            case 'nested':
                if (isset($item['children']) && is_array($item['children'])) {
                    $result[$key] = formatJsonRecursive($item['children']);
                }
                break;
            case 'unchanged':
                $result[$key] = [
                    'type' => 'unchanged',
                    'value' => $item['value'] ?? null
                ];
                break;
            case 'changed':
                $result[$key] = [
                    'type' => 'updated',
                    'oldValue' => $item['oldValue'] ?? null,
                    'newValue' => $item['newValue'] ?? null
                ];
                break;
            case 'add':
                $result[$key] = [
                    'type' => 'added',
                    'value' => $item['value'] ?? null
                ];
                break;
            case 'deleted':
                $result[$key] = [
                    'type' => 'removed',
                    'value' => $item['value'] ?? null
                ];
                break;
        }
    }

    return $result;
}
