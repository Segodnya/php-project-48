<?php

namespace Hexlet\Code\Parsers;

use Symfony\Component\Yaml\Yaml;

/**
 * Parses a JSON file and returns its content as an associative array.
 *
 * @param string $filepath The path to the JSON file.
 * @return array<string, mixed> The parsed JSON data.
 * @throws \Exception If the file does not exist or the JSON is invalid.
 */
function parseJsonFile(string $filepath): array
{
    if (!file_exists($filepath)) {
        throw new \Exception("File does not exist: $filepath");
    }

    $content = file_get_contents($filepath);

    if ($content === false) {
        throw new \Exception("Unable to read file: $filepath");
    }

    $data = json_decode($content, true);

    if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
        throw new \Exception("Error parsing JSON: " . json_last_error_msg());
    }

    return $data;
}

/**
 * Parses a YAML file and returns its content as an associative array.
 *
 * @param string $filepath The path to the YAML file.
 * @return array<string, mixed> The parsed YAML data.
 * @throws \Exception If the file does not exist or parsing fails.
 */
function parseYamlFile(string $filepath): array
{
    if (!file_exists($filepath)) {
        throw new \Exception("File does not exist: $filepath");
    }

    $content = file_get_contents($filepath);

    if ($content === false) {
        throw new \Exception("Unable to read file: $filepath");
    }

    $data = Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);

    return (array) $data;
}
