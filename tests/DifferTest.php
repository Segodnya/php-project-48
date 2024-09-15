<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

use function Hexlet\Code\Differ\diff;
use function Hexlet\Code\Formatters\format;

class DifferTest extends TestCase
{
    private const FIXTURES_DIR = __DIR__ . '/Fixtures/';

    /**
     * Provides data for genDiff tests.
     *
     * @return array<int, array{0: array<string, mixed>, 1: array<string, mixed>, 2: string}>
     */
    public function genDiffProvider(): array
    {
        $expectedDiff1 = file_get_contents(self::FIXTURES_DIR . 'expected_diff_1.txt');
        $expectedDiff2 = file_get_contents(self::FIXTURES_DIR . 'expected_diff_2.txt');

        if ($expectedDiff1 === false || $expectedDiff2 === false) {
            throw new \RuntimeException('Could not read expected diff file.');
        }

        // Safely handle JSON data
        $file1Json = file_get_contents(self::FIXTURES_DIR . 'file1.json');
        $file2Json = file_get_contents(self::FIXTURES_DIR . 'file2.json');
        $file3Json = file_get_contents(self::FIXTURES_DIR . 'file3.json');
        $file4Json = file_get_contents(self::FIXTURES_DIR . 'file4.json');

        if ($file1Json === false || $file2Json === false || $file3Json === false || $file4Json === false) {
            throw new \RuntimeException('Could not read JSON files.');
        }

        $jsonData1 = json_decode($file1Json, true);
        $jsonData2 = json_decode($file2Json, true);
        $jsonData3 = json_decode($file3Json, true);
        $jsonData4 = json_decode($file4Json, true);

        // Ensure that JSON decoding did not fail
        if (!is_array($jsonData1) || !is_array($jsonData2) || !is_array($jsonData3) || !is_array($jsonData4)) {
            throw new \RuntimeException('Could not decode JSON files.');
        }

        // Safely handle YAML data
        $yamlData1 = Yaml::parseFile(self::FIXTURES_DIR . 'file1.yml');
        $yamlData2 = Yaml::parseFile(self::FIXTURES_DIR . 'file2.yaml');
        $yamlData3 = Yaml::parseFile(self::FIXTURES_DIR . 'file3.yaml');
        $yamlData4 = Yaml::parseFile(self::FIXTURES_DIR . 'file4.yml');

        // Ensure YAML parsing did not fail
        if (!is_array($yamlData1) || !is_array($yamlData2) || !is_array($yamlData3) || !is_array($yamlData4)) {
            throw new \RuntimeException('Could not parse YAML files.');
        }

        return [
            [$jsonData1, $jsonData2, trim($expectedDiff1)],
            [$jsonData1, $yamlData2, trim($expectedDiff1)],
            [$yamlData1, $yamlData2, trim($expectedDiff1)],
            [$yamlData1, $jsonData2, trim($expectedDiff1)],
            [$jsonData3, $jsonData4, trim($expectedDiff2)],
            [$jsonData3, $yamlData4, trim($expectedDiff2)],
            [$yamlData3, $yamlData4, trim($expectedDiff2)],
            [$yamlData3, $jsonData4, trim($expectedDiff2)],
        ];
    }

    /**
     * Test the genDiff function.
     *
     * @dataProvider genDiffProvider
     *
     * @param array<string, mixed> $data1
     * @param array<string, mixed> $data2
     * @param string $expectedDiff
     */
    public function testGenDiff(array $data1, array $data2, string $expectedDiff): void
    {
        // default = stylish
        $result = format(diff($data1, $data2));
        $this->assertEquals($expectedDiff, trim($result));

        // Test with different formats (stylish)
        $stylishResult = format(diff($data1, $data2), 'stylish');
        $this->assertEquals($expectedDiff, trim($stylishResult));
    }

    public function testPlainFormat(): void
    {
        $expectedPlainDiff = file_get_contents(self::FIXTURES_DIR . 'expected_plain_diff.txt');

        if ($expectedPlainDiff === false) {
            throw new \RuntimeException('Could not read expected plain diff file.');
        }

        $file3Content = file_get_contents(self::FIXTURES_DIR . 'file3.json');
        $file4Content = file_get_contents(self::FIXTURES_DIR . 'file4.json');

        if ($file3Content === false || $file4Content === false) {
            throw new \RuntimeException('Could not read JSON files.');
        }

        $jsonData1 = json_decode($file3Content, true);
        $jsonData2 = json_decode($file4Content, true);

        if (!is_array($jsonData1) || !is_array($jsonData2)) {
            throw new \RuntimeException('Could not decode JSON files.');
        }

        $plainResult = format(diff($jsonData1, $jsonData2), 'plain');
        $this->assertEquals(trim($expectedPlainDiff), trim($plainResult));
    }
}
