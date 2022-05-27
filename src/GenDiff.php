<?php

const NEW_LINE_PREFIX = "  + ";
const DEL_LINE_PREFIX = "  - ";
const UNCH_LINE_PREFIX = "    ";

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $firstJson = json_decode(file_get_contents($pathToFile1), true);
    $secondJson = json_decode(file_get_contents($pathToFile2), true);
    ksort($firstJson);
    ksort($secondJson);
    $output = "{";
    $modified = array_diff_assoc($secondJson, $firstJson);
    $deleted = array_diff_assoc($firstJson, $secondJson);
    foreach ($firstJson as $key => $value) {
        $currentRecord = getProperString($key, $value);
        if (array_key_exists($key, $modified)) {
            $newRecord = getProperString($key, $secondJson[$key], NEW_LINE_PREFIX);
            unset($modified[$key]);
            $currentRecord = DEL_LINE_PREFIX . $currentRecord . PHP_EOL . $newRecord;
        } elseif (array_key_exists($key, $deleted)) {
            $currentRecord = DEL_LINE_PREFIX . $currentRecord;
        } else {
            $currentRecord = UNCH_LINE_PREFIX . $currentRecord;
        }
        $output .= PHP_EOL . $currentRecord;
    }
    foreach ($modified as $key => $value) {
        $newRecord = getProperString($key, $value, NEW_LINE_PREFIX);
        $output .= PHP_EOL . $newRecord;
    }
    return $output . PHP_EOL . "}";
}

function getProperString(string $key, $value, string $prefix = ""): string
{
    return is_bool($value) ? "{$prefix}{$key}: " . var_export($value, true) : "{$prefix}{$key}: {$value}";
}
