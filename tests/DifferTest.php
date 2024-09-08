<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

use function Hexlet\Code\Differ\diff;

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
        $expectedDiff = file_get_contents(self::FIXTURES_DIR . 'expected_diff_1.txt');
        if ($expectedDiff === false) {
            throw new \RuntimeException('Could not read expected diff file.');
        }

        // Safely handle JSON data
        $file1Json = file_get_contents(self::FIXTURES_DIR . 'file1.json');
        $file2Json = file_get_contents(self::FIXTURES_DIR . 'file2.json');

        if ($file1Json === false || $file2Json === false) {
            throw new \RuntimeException('Could not read JSON files.');
        }

        $jsonData1 = json_decode($file1Json, true);
        $jsonData2 = json_decode($file2Json, true);

        // Ensure that JSON decoding did not fail
        if (!is_array($jsonData1) || !is_array($jsonData2)) {
            throw new \RuntimeException('Could not decode JSON files.');
        }

        // Safely handle YAML data
        $yamlData1 = Yaml::parseFile(self::FIXTURES_DIR . 'file1.yml');
        $yamlData2 = Yaml::parseFile(self::FIXTURES_DIR . 'file2.yaml');

        // Ensure YAML parsing did not fail
        if (!is_array($yamlData1) || !is_array($yamlData2)) {
            throw new \RuntimeException('Could not parse YAML files.');
        }

        return [
            [$jsonData1, $jsonData2, trim($expectedDiff)],
            [$jsonData1, $yamlData2, trim($expectedDiff)],
            [$yamlData1, $yamlData2, trim($expectedDiff)],
            [$yamlData1, $jsonData2, trim($expectedDiff)],
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
        $result = diff($data1, $data2);
        $this->assertEquals(trim($expectedDiff), trim($result));
    }
}
