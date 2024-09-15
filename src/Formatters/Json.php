<?php

namespace Differ\Formatters\Json;

/**
 * Formats the diff array into a JSON string representation.
 *
 * @param array<int, array<string, mixed>> $diff The diff array to format
 * @return string The formatted JSON string
 * @throws \JsonException If JSON encoding fails
 */
function formatJson(array $diff): string
{
    $result = formatJsonRecursive($diff);
    return json_encode($result, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
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
        $key = $item['key'];

        switch ($item['type']) {
            case 'nested':
                assert(is_array($item['children']), "Children must be an array");
                $result[$key] = formatJsonRecursive($item['children']);
                break;

            case 'unchanged':
                $result[$key] = [
                    'type' => 'unchanged',
                    'value' => $item['value']
                ];
                break;

            case 'changed':
                $result[$key] = [
                    'type' => 'changed',
                    'oldValue' => $item['oldValue'],
                    'newValue' => $item['newValue']
                ];
                break;

            case 'added':
                $result[$key] = [
                    'type' => 'added',
                    'value' => $item['value']
                ];
                break;

            case 'deleted':
                $result[$key] = [
                    'type' => 'removed',
                    'value' => $item['value']
                ];
                break;
        }
    }

    return $result;
}
