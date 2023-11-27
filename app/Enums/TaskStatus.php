<?php

namespace App\Enums;

enum TaskStatus: string {
    case TODO = 'todo';
    case DONE = 'done';

    public static function toArray()
    {
        $values = [];
        foreach (self::cases() as $props) {
            array_push($values, $props->value);
        }
        return $values;
    }
}
