<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    /**
     * @dataProvider diffDataProvider
     */
    public function testGenDiff(string $inputFormat1, string $inputFormat2, string $outputFormat): void
    {
        $file1 = "file1.{$inputFormat1}";
        $file2 = "file2.{$inputFormat2}";
        $expectedFile = "diff.{$outputFormat}";

        $expected = file_get_contents(__DIR__ . "/Fixtures/{$expectedFile}");
        $this->assertNotFalse($expected);
        $actual = genDiff(__DIR__ . "/Fixtures/{$file1}", __DIR__ . "/Fixtures/{$file2}", $outputFormat);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array<string, array{string, string, string}>
     */
    public function diffDataProvider(): array
    {
        return [
            'JSON and YAML to Plain' => ['json', 'yaml', 'plain'],
            'YAML and JSON to Stylish' => ['yaml', 'json', 'stylish'],
            'JSON and JSON to Plain' => ['json', 'json', 'plain'],
            'YAML and YAML to Stylish' => ['yaml', 'yaml', 'stylish'],
            'JSON and JSON to JSON' => ['json', 'json', 'json'],
            'YAML and YAML to JSON' => ['yaml', 'yaml', 'json'],
        ];
    }
}
