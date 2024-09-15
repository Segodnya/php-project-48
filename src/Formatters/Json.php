<?php

namespace Differ\Formatters\Json;

/**
 * Summary of Differ\Formatters\Json\formatJson
 * @param mixed $diffTree
 * @throws \Exception
 * @return string
 */
function formatJson($diffTree): string
{
    $result = json_encode($diffTree);
    if (is_string($result)) {
        return $result;
    } else {
        throw new \Exception("Impossible to encode data into JSON.");
    }
}
