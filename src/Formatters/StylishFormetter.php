<?php

namespace Formatter\StylishFormat;

const LEVEL_PADDING = "    ";
const NEW_LINE_PREFIX = "  + ";
const DEL_LINE_PREFIX = "  - ";

function format(array $diff, int $level = 0): string
{
    $currentPadding = str_repeat(LEVEL_PADDING, $level);
    $output = "{" . PHP_EOL;
    foreach ($diff as $key => $value) {
        $isLeaf = $value['is_leaf'];
        if ($isLeaf) {
            if (!array_key_exists('new_value', $value)) {
                $output .= getProperString($key, $value['old_value'], $level, DEL_LINE_PREFIX);
            } elseif (!array_key_exists('old_value', $value)) {
                $output .= getProperString($key, $value['new_value'], $level, NEW_LINE_PREFIX);
            } else {
                $oldValue = $value['old_value'];
                $newValue = $value['new_value'];
                if ($newValue == $oldValue) {
                    $output .= getProperString($key, $value['old_value'], $level, LEVEL_PADDING);
                } else {
                    $output .= getProperString($key, $value['old_value'], $level, DEL_LINE_PREFIX);
                    $output .= getProperString($key, $value['new_value'], $level, NEW_LINE_PREFIX);
                }
            }
        } else {
            $output .= getProperString($key, format($value['value'], $level + 1), $level + 1);
        }
    }
    return $output . $currentPadding . '}';
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
        $value = $value ?? 'null';
        $value = is_bool($value) ? var_export($value, true) : $value;
        if (!empty($value)) {
            $output .= " {$value}";
        }
        $output .= PHP_EOL;
    }
    return $output;
}
