<?php

namespace Differ\Formatter\PlainFormat;

const PREFIX = 'Property';

function format(array $diff, string $keyPrefix = ""): string
{
    $fnComplex = function (array $value, string $keyPrefix): string {
        $newKeyPrefix = $keyPrefix === "" ? $value['key'] : "{$keyPrefix}.{$value['key']}";
        return format($value['children'], $newKeyPrefix);
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

    $fnFormat = function ($value) use ($fnComplex, $fnEdited, $fnAddedDeleted, $keyPrefix) {
        switch ($value['flag']) {
            case 'complex_value':
                return $fnComplex($value, $keyPrefix);
            case 'updated':
                return $fnEdited($value, $keyPrefix);
            case 'added':
            case 'removed':
                return $fnAddedDeleted($value, $keyPrefix);
            default:
                throw new \Exception("wrong type of the value");
        }
    };
    $onlyChanged = array_filter($diff, fn($e) => $e['flag'] != 'unchanged');
    return implode(PHP_EOL, array_map($fnFormat, $onlyChanged));
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
