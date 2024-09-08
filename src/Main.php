<?php

namespace Differ\Differ;

use function Hexlet\Code\Differ\diff;
use function Hexlet\Code\Parsers\parseJsonFile;
use function Hexlet\Code\Parsers\parseYamlFile;

/**
 * @return array<string, mixed> The parsed data from the file.
 */
function getFileData(string $filepath): array
{
    $ext = pathinfo($filepath, PATHINFO_EXTENSION);

    if ($ext === 'json') {
        return parseJsonFile($filepath);
    } elseif ($ext === 'yml' || $ext === 'yaml') {
        return parseYamlFile($filepath);
    }

    throw new \Exception("Unsupported file type: $ext");
}

function genDiff(string $pathFile1, string $pathFile2): string
{
    $arrFile1 = getFileData($pathFile1);
    $arrFile2 = getFileData($pathFile2);

    $diff = diff($arrFile1, $arrFile2);

    return $diff;
}
