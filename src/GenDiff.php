<?php

use function Formatter\StylishFormat\format as stylishFormat;
use function Parser\parse;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $firstJson = parse($pathToFile1);
    $secondJson = parse($pathToFile2);
    $jsonDifference = getJsonDifference($firstJson, $secondJson);
    return getFormattedDifference($jsonDifference, $format);
}

function getFormattedDifference(array $jsonDifference, string $format): string
{
    switch ($format) {
        case 'stylish':
        default:
            return stylishFormat($jsonDifference);
    }
}

function getJsonDifference(array $firstJson, array $secondJson): array
{
    $jsonDifference = [];
    foreach ($firstJson as $key => $value) {
        if (is_array($value)) {
            ksort($value);
        }
        if (array_key_exists($key, $secondJson)) {
            $newValue = $secondJson[$key];
            if (is_array($newValue)) {
                ksort($newValue);
            }
            if (is_array($value) && is_array($newValue)) {
                $jsonDifference[$key] = [
                    'value' => getJsonDifference($value, $newValue),
                    'is_leaf' => false
                ];
            } else {
                $jsonDifference[$key] = [
                    'old_value' => $value,
                    'new_value' => $newValue,
                    'is_leaf' => true
                ];
            }
        } else {
            $jsonDifference[$key] = [
                'old_value' => $value,
                'is_leaf' => true
            ];
        }
    }
    $newElements = array_diff_key($secondJson, $firstJson);
    foreach ($newElements as $key => $value) {
        $jsonDifference[$key] = [
            'new_value' => $value,
            'is_leaf' => true
        ];
    }
    ksort($jsonDifference);
    return $jsonDifference;
}
