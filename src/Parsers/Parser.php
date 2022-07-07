<?php

namespace Parser;

use Symfony\Component\Yaml\Yaml;

function parse(string $filePath)
{
    $extension = substr($filePath, strripos($filePath, ".") + 1);
    if (!str_starts_with($filePath, "/")) {
        $filePath = "./" . $filePath;
    }
    switch ($extension) {
        case "json":
            $fileContent = json_decode(file_get_contents(realpath($filePath)), true);
            break;
        case "yml":
        case "yaml":
            $fileContent = Yaml::parse(file_get_contents(realpath($filePath)));
            break;
        default:
            throw new \Exception("Unknown file format");
    }
    return $fileContent;
}
