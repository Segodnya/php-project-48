<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;

use function Hexlet\Code\Differ\genDiff;

class DifferTest extends TestCase
{
    /**
     * @dataProvider diffDataProvider
     */
    public function testGenDiff(string $file1, string $file2, string $format, string $expectedFile): void
    {
        $expected = file_get_contents(__DIR__ . "/Fixtures/{$expectedFile}");
        $this->assertNotFalse($expected);
        $actual = genDiff(__DIR__ . "/Fixtures/{$file1}", __DIR__ . "/Fixtures/{$file2}", $format);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array<string, array{string, string, string, string}>
     */
    public function diffDataProvider(): array
    {
        return [
            'JSON and YAML to Plain' => ['file1.json', 'file2.yaml', 'plain', 'diff.plain'],
            'YAML and JSON to Stylish' => ['file1.yaml', 'file2.json', 'stylish', 'diff.stylish'],
            'JSON and JSON to Plain' => ['file1.json', 'file2.json', 'plain', 'diff.plain'],
            'YAML and YAML to Stylish' => ['file1.yaml', 'file2.yaml', 'stylish', 'diff.stylish'],
        ];
    }
}
