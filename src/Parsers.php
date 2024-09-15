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
