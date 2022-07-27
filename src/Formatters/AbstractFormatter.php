<?php

namespace Formatter\AbstractFormatter;

function doFormat(array $diff, $fnComplex, $fnEdited, $fnAdded, $fnDeleted, $fnUnchanged, mixed $levelPrefix): array
{
    return array_reduce(
        $diff,
        function ($acc, $value) use ($fnComplex, $fnEdited, $fnAdded, $fnDeleted, $fnUnchanged, $levelPrefix) {
            switch ($value['flag']) {
                case 'complex_value':
                    $record = $fnComplex($value, $levelPrefix);
                    if ($record != null) {
                        $acc[] = $record;
                    }
                    break;
                case 'updated':
                    $acc[] = $fnEdited($value, $levelPrefix);
                    break;
                case 'added':
                    $acc[] = $fnAdded($value, $levelPrefix);
                    break;
                case 'removed':
                    $acc[] = $fnDeleted($value, $levelPrefix);
                    break;
                case 'unchanged':
                    $acc[] = $fnUnchanged($value, $levelPrefix);
                    break;
            }
            return $acc;
        },
        []
    );
}
