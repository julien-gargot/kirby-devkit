<?php

return [
    'props' => [
        'default' => function ($default = null) {
            return $this->toDate($default);
        },
        'icon' => function (string $icon = "calendar") {
            return $icon;
        },
        'max' => function (string $max = null) {
            return $this->toDate($max);
        },
        'min' => function (string $min = null) {
            return $this->toDate($min);
        },
        'time' => function ($time = false) {
            return $time;
        },
        'value' => function ($value = null) {
            return $this->toDate($value);
        },
    ],
    'computed' => [
        'format' => function () {
            return $this->props['format'] ?? ($this->time() === false ? 'Y-m-d' : 'Y-m-d H:i');
        }
    ],
    'methods' => [
        'toDate' => function ($value) {
            if ($value !== null && $date = strtotime($value)) {
                return date(DATE_W3C, $date);
            }
        },
        'toString' => function ($value): string {
            if ($value !== null && $date = strtotime($value)) {
                return date($this->format(), $date);
            }

            return '';
        },
    ],
    'validations' => [
        'date'
    ]
];
