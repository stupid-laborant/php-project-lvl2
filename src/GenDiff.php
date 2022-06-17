<?php

use function Parser\parse;

const NEW_LINE_PREFIX = "  + ";
const DEL_LINE_PREFIX = "  - ";
const UNCH_LINE_PREFIX = "    ";

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $firstJson = parse($pathToFile1);
    $secondJson = parse($pathToFile2);
    ksort($firstJson);
    ksort($secondJson);
    $output = "{" . PHP_EOL;

    $unmodified = array_intersect_assoc($firstJson, $secondJson);
    $modified = array_diff_assoc(array_intersect_key($firstJson, $secondJson), $unmodified);
    $added = array_diff_key($secondJson, $firstJson);
    foreach ($firstJson as $key => $value) {
        if (array_key_exists($key, $unmodified)) {
            $output .= getProperString($key, $value, UNCH_LINE_PREFIX);
        } else {
            $output .= getProperString($key, $value, DEL_LINE_PREFIX);
            if (array_key_exists($key, $modified)) {
                $output .= getProperString($key, $secondJson[$key], NEW_LINE_PREFIX);
            }
        }
    }

    foreach ($added as $key => $value) {
        $output .= getProperString($key, $value, NEW_LINE_PREFIX);
    }

    return $output . "}";
}

function getProperString(string $key, $value, string $prefix = ""): string
{
    $output = is_bool($value) ? "{$prefix}{$key}: " . var_export($value, true) : "{$prefix}{$key}: {$value}";
    return $output . PHP_EOL;
}
