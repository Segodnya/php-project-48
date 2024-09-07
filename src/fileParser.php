<?php

function parseJsonFile($filepath)
{
    if (!file_exists($filepath)) {
        throw new Exception("File does not exist: $filepath");
    }

    $content = file_get_contents($filepath);
    $data = json_decode($content, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Error parsing JSON: " . json_last_error_msg());
    }

    return $data;
}
