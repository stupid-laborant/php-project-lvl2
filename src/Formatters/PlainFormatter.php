<?php

namespace Differ\Formatter\PlainFormat;

use function Differ\Formatter\AbstractFormatter\doFormat;

const PREFIX = 'Property';

function format(array $diff, string $keyPrefix = ""): string
{
    $fnComplex = function (array $value, string $keyPrefix): string {
        $NewKeyPrefix = $keyPrefix === "" ? $value['key'] : "{$keyPrefix}.{$value['key']}";
        return format($value['children'], $NewKeyPrefix);
    };

    $fnEdited = function (array $value, string $keyPrefix): string {
        $NewKeyPrefix = $keyPrefix === "" ? $value['key'] : "{$keyPrefix}.{$value['key']}";
        $new = $value['new_value'];
        $old = $value['old_value'];
        return getUpdateString($NewKeyPrefix, $old, $new);
    };

    $fnAddedDeleted = function (array $value, string $keyPrefix): string {
        $NewKeyPrefix = $keyPrefix === "" ? $value['key'] : "{$keyPrefix}.{$value['key']}";
        return getAddDeleteString($NewKeyPrefix, $value['value'], $value['flag']);
    };
    $onlyChanged = array_filter($diff, fn($e) => $e['flag'] != 'unchanged');
    $output = doFormat($onlyChanged, $fnComplex, $fnEdited, $fnAddedDeleted, $fnAddedDeleted, fn($e) => $e, $keyPrefix);
    return implode(PHP_EOL, $output);
}


function getAddDeleteString(string $key, mixed $value, string $operation): string
{
    $output = PREFIX . " '{$key}' was " . $operation;
    if ($operation == 'added') {
        return $output . " with value: " . prepareValue($value);
    }
    return $output;
}

function getUpdateString(string $key, mixed $oldValue, mixed $newValue): string
{
    return  PREFIX . " '{$key}' was updated. From " . prepareValue($oldValue) . " to " . prepareValue($newValue);
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
