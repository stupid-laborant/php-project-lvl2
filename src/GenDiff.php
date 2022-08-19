<?php

namespace Differ\Differ;

use function Differ\Parser\parse;
use function Differ\Formatter\getFormatted;
use function Functional\sort;

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
    $firstJsonKeys = array_keys($firstJson);
    $secondJsonKeys = array_keys($secondJson);
    $uniqueKeys = array_unique(array_merge($firstJsonKeys, $secondJsonKeys));
    $uniqueSortedKeys = sort($uniqueKeys, fn($s1, $s2) => strcmp($s1, $s2));
    return array_map(function ($key) use ($firstJson, $secondJson) {
        if (array_key_exists($key, $firstJson) && array_key_exists($key, $secondJson)) {
            $oldValue = $firstJson[$key];
            $newValue = $secondJson[$key];
            if (is_array($oldValue) && is_array($newValue)) {
                return [
                    'key' => $key,
                    'children' => getJsonDifference($oldValue, $newValue),
                    'flag' => 'complex_value'
                ];
            } else {
                if ($oldValue !== $newValue) {
                    return [
                        'key' => $key,
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                        'flag' => 'updated'
                    ];
                } else {
                    return [
                        'key' => $key,
                        'value' => $oldValue,
                        'flag' => 'unchanged'
                    ];
                }
            }
        } elseif (array_key_exists($key, $firstJson)) {
            return [
                'key' => $key,
                'value' => $firstJson[$key],
                'flag' => 'removed'
            ];
        } else {
            return [
                'key' => $key,
                'value' => $secondJson[$key],
                'flag' => 'added'
            ];
        }
    }, $uniqueSortedKeys);
}
