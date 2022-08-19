<?php

namespace Differ\Formatter\AbstractFormatter;

function doFormat(
    array $diff,
    mixed $fnComplex,
    mixed $fnEdited,
    mixed $fnAdded,
    mixed $fnDeleted,
    mixed $fnUnchanged,
    mixed $levelPrefix
): array {
    return array_map(
        function ($value) use ($fnComplex, $fnEdited, $fnAdded, $fnDeleted, $fnUnchanged, $levelPrefix) {
            switch ($value['flag']) {
                case 'complex_value':
                    return $fnComplex($value, $levelPrefix);
                case 'updated':
                    return $fnEdited($value, $levelPrefix);
                case 'added':
                    return $fnAdded($value, $levelPrefix);
                case 'removed':
                    return $fnDeleted($value, $levelPrefix);
                case 'unchanged':
                    return $fnUnchanged($value, $levelPrefix);
                default:
                    throw new \Exception("wrong type of the value");
            }
        },
        $diff
    );
}
