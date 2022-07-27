<?php

namespace Formatter\JsonFormatter;

use function Formatter\AbstractFormatter\doFormat;

const PREFIX = "  ";

function format(array $diff, string $prefix = ""): string
{
    $fnFormat = function (array $array, string $prefix) use (&$fnFormat): string {
        $output = [];
        $prefix = $prefix . PREFIX;
        $output[] = $prefix . "{" . PHP_EOL;
        foreach ($array as $key => $value) {
            $newPrefix = $prefix . PREFIX;
            if ($key == 'children') {
                $output[] = "{$newPrefix}\"{$key}\": {" . PHP_EOL;
                $output[] = format($value, $newPrefix) . PHP_EOL;
            } else {
                $output[] = getProperString($key, $value, $prefix);
            }
        }
        $output[] = $prefix . "}" . PHP_EOL;
        return implode("", $output);
    };
    $output = [];
    if ($prefix == '') {
        $output[] = "{" . PHP_EOL;
    }
    $output = array_merge($output, doFormat($diff, $fnFormat, $fnFormat, $fnFormat, $fnFormat, $fnFormat, $prefix));
    $output[] = $prefix . "}";
    return implode("", $output);
}

function getProperString(string $key, $value, string $prefix): string
{
    $newPrefix = $prefix . PREFIX;
    $output = "{$newPrefix}\"{$key}\":";
    if (is_array($value)) {
        ksort($value);
        $output .= " {" . PHP_EOL;
        foreach ($value as $subKey => $subValue) {
            $output .= getProperString($subKey, $subValue, $newPrefix);
        }
        $output .= $newPrefix . "}";
    } else {
        $valueToString = prepareValue($value);
        if (!empty($valueToString)) {
            $output .= " {$valueToString}";
        }
    }
    $output .= PHP_EOL;
    return $output;
}

function prepareValue(mixed $val)
{
    if ($val === null) {
        return 'null';
    }
    if (is_bool($val)) {
        return var_export($val, true);
    }
    if (is_string($val)) {
        return "\"$val\"";
    }
    return $val;
}
