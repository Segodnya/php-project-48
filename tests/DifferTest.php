<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

use function Hexlet\Code\Differ\diff;
use function Hexlet\Code\Formatters\format;

class DifferTest extends TestCase
{
    /**
     * @dataProvider diffDataProvider
     */
    public function testGenDiff(string $file1, string $file2, string $format, string $expectedFile): void
    {
        $expected = file_get_contents(__DIR__ . "/Fixtures/{$expectedFile}");
        $file1Content = file_get_contents(__DIR__ . "/Fixtures/{$file1}");
        $file2Content = file_get_contents(__DIR__ . "/Fixtures/{$file2}");

        $this->assertNotFalse($expected);
        $this->assertNotFalse($file1Content);
        $this->assertNotFalse($file2Content);

        $data1 = $this->parseFile($file1, $file1Content);
        $data2 = $this->parseFile($file2, $file2Content);

        $this->assertIsArray($data1);
        $this->assertIsArray($data2);

        $actual = format(diff($data1, $data2), $format);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array<string, array{string, string, string, string}>
     */
    public function diffDataProvider(): array
    {
        return [
            'JSON and YAML to Plain' => ['file5.json', 'file6.yaml', 'plain', 'diff.plain'],
            'YAML and JSON to Stylish' => ['file5.yaml', 'file6.json', 'stylish', 'diff.stylish'],
            'JSON and JSON to Plain' => ['file5.json', 'file6.json', 'plain', 'diff.plain'],
            'YAML and YAML to Stylish' => ['file5.yaml', 'file6.yaml', 'stylish', 'diff.stylish'],
        ];
    }

    /**
     * @return array<mixed>
     */
    private function parseFile(string $filename, string $content): array
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $parsedContent = $extension === 'json'
            ? json_decode($content, true)
            : Yaml::parse($content);

        return is_array($parsedContent) ? $parsedContent : [];
    }
}
