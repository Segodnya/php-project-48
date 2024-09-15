<?php

namespace Hexlet\Code\Formatters;

use function Hexlet\Code\Formatters\Stylish\formatStylish;
use function Hexlet\Code\Formatters\Plain\formatPlain;

/**
 * Formats the diff array into a string representation.
 *
 * @param array<int, array<string, mixed>> $diff The diff array to format
 * @param string $format The format to use (default: 'stylish')
 * @return string The formatted diff string
 * @throws \Exception If an unsupported format is specified
 */
function format(array $diff, string $format = 'stylish'): string
{
    switch ($format) {
        case 'stylish':
            return formatStylish($diff);
        case 'plain':
            return formatPlain($diff);
        default:
            throw new \Exception("Unsupported format: $format");
    }
}
