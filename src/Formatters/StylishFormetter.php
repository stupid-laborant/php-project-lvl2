<?php

namespace Formatter\StylishFormat;

use function Formatter\AbstractFormatter\doFormat;

const LEVEL_PADDING = "    ";
const NEW_LINE_PREFIX = "  + ";
const DEL_LINE_PREFIX = "  - ";

function format(array $diff, int $level = 0): string
{
    $currentPadding = str_repeat(LEVEL_PADDING, $level);
    $output = "{" . PHP_EOL;

    $fnComplex = function (array $value, int $level): string {
        return getProperString($value['key'], format($value['children'], $level + 1), $level + 1);
    };

    $fnEdited = function (array $value, int $level): string {
        $key = $value['key'];
        return getProperString($key, $value['old_value'], $level, DEL_LINE_PREFIX)
            . getProperString($key, $value['new_value'], $level, NEW_LINE_PREFIX);
    };

    $fnAdded = function (array $value, int $level): string {
        return getProperString($value['key'], $value['value'], $level, NEW_LINE_PREFIX);
    };

    $fnDeleted = function (array $value, int $level): string {
        return getProperString($value['key'], $value['value'], $level, DEL_LINE_PREFIX);
    };

    $fnUnchanged = function (array $value, int $level): string {
        return getProperString($value['key'], $value['value'], $level, LEVEL_PADDING);
    };
    $output .= implode(doFormat($diff, $fnComplex, $fnEdited, $fnAdded, $fnDeleted, $fnUnchanged, $level));
    return  $output . $currentPadding . '}';
}

function getProperString(string $key, $value, int $level, string $prefix = ""): string
{
    $padding = str_repeat(LEVEL_PADDING, $level);
    $output = "{$padding}{$prefix}{$key}:";
    if (is_array($value)) {
        ksort($value);
        $output .= " {" . PHP_EOL;
        foreach ($value as $subKey => $subValue) {
            $output .= getProperString($subKey, $subValue, $level + 1, LEVEL_PADDING);
        }
        $output .=  $padding . LEVEL_PADDING . "}" . PHP_EOL;
    } else {
        $preparedValue = prepareValue($value);
        if (!empty($preparedValue)) {
            $output .= " {$preparedValue}";
        }
        $output .= PHP_EOL;
    }
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
    return $val;
}
