<?php

namespace Hexlet\Code\Differ;

/**
 * Generates a diff between two arrays.
 *
 * @param array<string, mixed> $data1
 * @param array<string, mixed> $data2
 * @return array<int, array<string, mixed>>
 */
function diff(array $data1, array $data2): array
{
    $keys = array_unique(array_merge(array_keys($data1), array_keys($data2)));

    // Sort keys alphabetically
    sort($keys);

    $result = array_map(
        function ($key) use ($data1, $data2) {
            if (!array_key_exists($key, $data1)) {
                return ['key' => $key, 'type' => 'add', 'value' => $data2[$key]];
            }

            if (!array_key_exists($key, $data2)) {
                return ['key' => $key, 'type' => 'deleted', 'value' => $data1[$key]];
            }

            if ($data1[$key] === $data2[$key]) {
                return ['key' => $key, 'type' => 'unchanged', 'value' => $data1[$key]];
            }

            if (is_array($data1[$key]) && is_array($data2[$key])) {
                return ['key' => $key, 'type' => 'nested', 'children' => diff($data1[$key], $data2[$key])];
            }

            return [
                'key' => $key,
                'type' => 'changed',
                'oldValue' => $data1[$key],
                'newValue' => $data2[$key]
            ];
        },
        $keys
    );

    return $result;
}
