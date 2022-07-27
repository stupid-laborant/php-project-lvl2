<?php

use function Parser\parse;
use function Formatter\getFormatted;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $firstJson = parse($pathToFile1);
    $secondJson = parse($pathToFile2);
    $jsonDifference = getJsonDifference($firstJson, $secondJson);
    return getFormattedDifference($jsonDifference, $format);
}

function getFormattedDifference(array $jsonDifference, string $format): string
{
    return getFormatted($jsonDifference, $format);
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
                $jsonDifference[] = [
                    'key' => $key,
                    'children' => getJsonDifference($value, $newValue),
                    'flag' => 'complex_value'
                ];
            } else {
                if ($value !== $newValue) {
                    $jsonDifference[] = [
                        'key' => $key,
                        'old_value' => $value,
                        'new_value' => $newValue,
                        'flag' => 'updated'
                    ];
                } else {
                    $jsonDifference[] = [
                        'key' => $key,
                        'value' => $value,
                        'flag' => 'unchanged'
                    ];
                }
            }
        } else {
            $jsonDifference[] = [
                'key' => $key,
                'value' => $value,
                'flag' => 'removed'
            ];
        }
    }
    $newElements = array_diff_key($secondJson, $firstJson);
    foreach ($newElements as $key => $value) {
        $jsonDifference[] = [
            'key' => $key,
            'value' => $value,
            'flag' => 'added'
        ];
    }
    usort($jsonDifference, fn($v1, $v2) => strcmp($v1['key'], $v2['key']));
    return $jsonDifference;
}
