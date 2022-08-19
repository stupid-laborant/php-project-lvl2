<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(string $filePath)
{
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    $realFilePath = str_starts_with($filePath, "/") ? realpath($filePath) : realpath("./{$filePath}");
    if ($realFilePath === false) {
        throw new \Exception($filePath . "path is invalid");
    }
    $fileContent = file_get_contents($realFilePath);
    if ($fileContent === false) {
        throw new \Exception("can't read file: {$realFilePath}");
    }
    switch ($extension) {
        case "json":
            return json_decode($fileContent, true);
        case "yml":
        case "yaml":
            return Yaml::parse($fileContent);
        default:
            throw new \Exception("Unknown file format");
    }
}
