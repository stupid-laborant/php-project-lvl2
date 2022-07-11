<?php

namespace Formatter\PlainFormat;

const PREFIX = 'Property';

function format(array $diff, array $keyPrefix = []): string
{
    $output = [];
    foreach ($diff as $key => $value) {
        $fullKeyName = $keyPrefix;
        $fullKeyName[] = $key;
        if (!$value['is_leaf']) {
            $output[] = format($value['value'], $fullKeyName);
        } else {
            if (!array_key_exists('new_value', $value)) {
                //only old
                $output[] = getAddDeleteString($fullKeyName, $value['old_value'], 'removed');
            } elseif (!array_key_exists('old_value', $value)) {
                //only new
                $output[] = getAddDeleteString($fullKeyName, $value['new_value'], 'added');
            } else {
                //both new & old
                $new = $value['new_value'];
                $old = $value['old_value'];
                if ($new !== $old) {
                    $output[] = getUpdateString($fullKeyName, $old, $new);
                }
            }
        }
    }
    return implode(PHP_EOL, $output);
}

function getAddDeleteString(array $key, mixed $value, string $operation): string
{
    $fullKeyStringName = implode(".", $key);
    $output = PREFIX . " '{$fullKeyStringName}' was " . $operation;
    if ($operation == 'added') {
        $output .= " with value: " . prepareValue($value);
    }
    return $output;
}

function getUpdateString(array $key, mixed $oldValue, mixed $newValue): string
{
    $nameWithPrefix = PREFIX . " '" . implode(".", $key);
    $output = $nameWithPrefix . "' was updated. From " . prepareValue($oldValue) . " to " . prepareValue($newValue);
    return  $output;
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
        return "'$val'";
    }
    if (is_array($val)) {
        return "[complex value]";
    }
    return $val;
}
