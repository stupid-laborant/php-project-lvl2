<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\Formatter\getFormatted;
use function Functional\sort;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $extensionFile1 = pathinfo($pathToFile1, PATHINFO_EXTENSION);
    $extensionFile2 = pathinfo($pathToFile2, PATHINFO_EXTENSION);
    $contentFile1 = getFileContent($pathToFile1);
    $contentFile2 = getFileContent($pathToFile2);
    $firstJson = parse($contentFile1, $extensionFile1);
    $secondJson = parse($contentFile2, $extensionFile2);
    $jsonDifference = getJsonDifference($firstJson, $secondJson);
    return getFormattedDifference($jsonDifference, $format);
}

function getFileContent(string $filePath): string
{
    $realFilePath = str_starts_with($filePath, "/") ? realpath($filePath) : realpath("./{$filePath}");
    if ($realFilePath === false) {
        throw new \Exception($filePath . "path is invalid");
    }
    $fileContent = file_get_contents($realFilePath);
    if ($fileContent === false) {
        throw new \Exception("can't read file: {$realFilePath}");
    }
    return $fileContent;
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
