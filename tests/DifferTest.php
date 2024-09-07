<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;

use function Hexlet\Code\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff()
    {
        $data1 = [
            "host" => "hexlet.io",
            "timeout" => 50,
            "proxy" => "123.234.53.22",
            "follow" => false,
        ];

        $data2 = [
            "host" => "hexlet.io",
            "timeout" => 20,
            "verbose" => true,
        ];

        $expectedDiff = "{
  - follow: false
    host: \"hexlet.io\"
  - proxy: \"123.234.53.22\"
  - timeout: 50
  + timeout: 20
  + verbose: true
}";

        $result = genDiff($data1, $data2);
        $this->assertEquals($expectedDiff, $result);
    }
}
