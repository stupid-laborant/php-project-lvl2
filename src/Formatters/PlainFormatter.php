<?php

namespace Formatter\PlainFormat;

use function Formatter\AbstractFormatter\doFormat;

const PREFIX = 'Property';

function format(array $diff, array $keyPrefix = []): string
{
    $fnComplex = function (array $value, array $keyPrefix): string {
        $keyPrefix[] = $value['key'];
        return format($value['children'], $keyPrefix);
    };

    $fnEdited = function (array $value, array $keyPrefix): string {
        $keyPrefix[] = $value['key'];
        $new = $value['new_value'];
        $old = $value['old_value'];
        return getUpdateString($keyPrefix, $old, $new);
    };

    $fnAddedDeleted = function (array $value, array $keyPrefix): string {
        $keyPrefix[] = $value['key'];
        return getAddDeleteString($keyPrefix, $value['value'], $value['flag']);
    };
    $diff = array_filter($diff, fn($e) => $e['flag'] != 'unchanged');
    $output = doFormat($diff, $fnComplex, $fnEdited, $fnAddedDeleted, $fnAddedDeleted, fn($e) => $e, $keyPrefix);
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
