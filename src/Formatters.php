<?php

namespace Formatter;

use function Formatter\StylishFormat\format as stylishFormat;
use function Formatter\PlainFormat\format as plainFormat;

function getFormatted(array $array, string $format): string
{
    switch ($format) {
        case 'plain':
            return plainFormat($array);
        case 'stylish':
        default:
            return stylishFormat($array);
    }
}
