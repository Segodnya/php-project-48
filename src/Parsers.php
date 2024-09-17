<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $dataType, string $content): object
{
    switch ($dataType) {
        case 'json':
            $parsed = json_decode($content);
            return is_object($parsed) ? $parsed : (object) $parsed;
        case 'yaml':
        case 'yml':
            $parsedYaml = Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
            return is_object($parsedYaml) ? $parsedYaml : (object) $parsedYaml;
        default:
            throw new \Exception("Unknown data type \"$dataType\".");
    }
}

function getFileExtension(string $path): string
{
    if (file_exists($path)) {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
    } else {
        throw new \Exception("File $path not exists.");
    }

    return $extension;
}

function getFileContent(string $path): string
{
    if (is_readable($path)) {
        $fileData = file_get_contents($path);
    } else {
        throw new \Exception("File $path not exists or not readable.");
    }

    if (is_string($fileData)) {
        return $fileData;
    } else {
        throw new \Exception("File $path content is not in string format.");
    }
}
