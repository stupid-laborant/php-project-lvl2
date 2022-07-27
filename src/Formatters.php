<?php

namespace Formatter;

use function Formatter\StylishFormat\format as stylishFormat;
use function Formatter\PlainFormat\format as plainFormat;
use function Formatter\JsonFormatter\format as jsonFormat;

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
