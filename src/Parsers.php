<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $fileContent, string $fileExtension)
{
    switch ($fileExtension) {
        case "json":
            return json_decode($fileContent, true);
        case "yml":
        case "yaml":
            return Yaml::parse($fileContent);
        default:
            throw new \Exception("Unknown file format");
    }
}
