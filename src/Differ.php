<?php

namespace Hexlet\Code\Differ;

function genDiff(array $data1, array $data2): string
{
    $keys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    sort($keys); // Sort keys alphabetically

    $result = [];

    foreach ($keys as $key) {
        if (array_key_exists($key, $data1) && array_key_exists($key, $data2)) {
            if ($data1[$key] !== $data2[$key]) {
                // Key has changed
                $result[] = sprintf("  - %s: %s", $key, json_encode($data1[$key]));
                $result[] = sprintf("  + %s: %s", $key, json_encode($data2[$key]));
            } else {
                // Key is unchanged
                $result[] = sprintf("    %s: %s", $key, json_encode($data1[$key]));
            }
        } elseif (array_key_exists($key, $data1)) {
            // Key only in data1 (removed)
            $result[] = sprintf("  - %s: %s", $key, json_encode($data1[$key]));
        } else {
            // Key only in data2 (added)
            $result[] = sprintf("  + %s: %s", $key, json_encode($data2[$key]));
        }
    }

    return "{\n" . implode("\n", $result) . "\n}";
}
