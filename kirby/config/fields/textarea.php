<?php

return [
    'props' => [
        'buttons' => function ($buttons = true) {
            return $buttons;
        },
        'counter' => function (bool $counter = true) {
            return $counter;
        },
        'maxlength' => function (int $maxlength = null) {
            return $maxlength;
        },
        'minlength' => function (int $minlength = null) {
            return $minlength;
        },
        'size' => function (string $size = null) {
            return $size;
        },
        'value' => function (string $value = null) {
            return trim($value);
        }
    ],
    'validations' => [
        'required',
        'minlength',
        'maxlength'
    ]
];
