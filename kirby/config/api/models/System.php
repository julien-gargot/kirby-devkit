<?php

use Kirby\Cms\System;

/**
 * Avatar
 */
return [
    'fields' => [
        'isOk' => function (System $system) {
            return $system->isOk();
        },
        'isInstalled' => function (System $system) {
            return $system->isInstalled();
        },
        'isLocal' => function (System $system) {
            return $system->isLocal();
        },
        'multilang' => function () {
            return $this->kirby()->option('languages', false) !== false;
        },
        'languages' => function () {
            return $this->kirby()->languages();
        },
        'license' => function (System $system) {
            $license = $system->license();
            return $license ? $license['type'] : null;
        },
        'requirements' => function (System $system) {
            return $system->toArray();
        },
        'title' => function () {
            return $this->site()->title()->value();
        },
        'translation' => function () {
            if ($user = $this->user()) {
                return $this->kirby()->translation($user->language());
            }

            return $this->kirby()->translation();
        },
        'kirbytext' => function () {
            return $this->kirby()->option('panel')['kirbytext'] ?? true;
        },
        'user' => function () {
            return $this->user();
        },
        'version' => function () {
            return $this->kirby()->version();
        }
    ],
    'type'   => System::class,
    'views'  => [
        'panel' => [
            'isOk',
            'isInstalled',
            'isLocal',
            'kirbytext',
            'languages' => 'compact',
            'license',
            'multilang',
            'requirements',
            'title',
            'translation',
            'user' => 'auth',
            'version'
        ]
    ],
];
