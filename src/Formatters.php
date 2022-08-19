<?php

namespace Differ\Formatter;

use function Differ\Formatter\JsonFormatter\format as jsonFormat;
use function Differ\Formatter\StylishFormat\format as stylishFormat;
use function Differ\Formatter\PlainFormat\format as plainFormat;

function getFormatted(array $array, string $format): string
{
    switch ($format) {
        case 'plain':
            return plainFormat($array);
        case 'json':
            return jsonFormat($array);
        case 'stylish':
        default:
            return stylishFormat($array);
    }
}
