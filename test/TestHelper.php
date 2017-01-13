<?php

namespace transitguide\api\test;

class TestHelper
{
    public static function getStrings(): array
    {
        return [
            'Basic' => 'test',
            'Long' => "This is an unusually long test string that has punctuation, a single qu'ote, and a diácritical mark",
            'Numeric' => '42',
            'Decimal' => '516.32',
            'Blank' => '',
        ];
    }
}
