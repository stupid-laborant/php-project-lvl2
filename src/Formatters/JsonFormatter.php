<?php

namespace Differ\Formatter\JsonFormatter;

function format(array $diff): string
{
    return json_encode($diff);
}
