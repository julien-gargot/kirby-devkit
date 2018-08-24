<?php

use Kirby\Cms\File;
use Kirby\Cms\Form;

/**
 * File
 */
return [
    'fields' => [
        'blueprint' => function (File $file) {
            return $file->blueprint();
        },
        'content' => function (File $file) {
            return $file->content()->toArray();
        },
        'dimensions' => function (File $file) {
            return $file->dimensions()->toArray();
        },
        'exists' => function (File $avatar) {
            return $avatar->exists();
        },
        'extension' => function (File $file) {
            return $file->extension();
        },
        'filename' => function (File $file) {
            return $file->filename();
        },
        'id' => function (File $file) {
            return $file->id();
        },
        'link' => function (File $file) {
            return $file->panelUrl(true);
        },
        'mime' => function (File $file) {
            return $file->mime();
        },
        'modified' => function (File $file) {
            return $file->modified('c');
        },
        'name' => function (File $file) {
            return $file->name();
        },
        'next' => function (File $file) {
            return $file->next();
        },
        'nextWithTemplate' => function (File $file) {
            return $file->nextAll()->template($file->template())->first();
        },
        'options' => function (File $file) {
            return $file->permissions()->toArray();
        },
        'prev' => function (File $file) {
            return $file->prev();
        },
        'prevWithTemplate' => function (File $file) {
            return $file->prevAll()->template($file->template())->last();
        },
        'niceSize' => function (File $file) {
            return $file->niceSize();
        },
        'parent' => function (File $file) {
            return $file->parent();
        },
        'parents' => function (File $file) {
            return $file->parents()->flip();
        },
        'template' => function (File $file) {
            return $file->template();
        },
        'size' => function (File $file) {
            return $file->size();
        },
        'type' => function (File $file) {
            return $file->type();
        },
        'url' => function (File $file) {
            return $file->url(true);
        },
    ],
    'type'  => File::class,
    'views' => [
        'default' => [
            'content',
            'dimensions',
            'exists',
            'extension',
            'filename',
            'id',
            'link',
            'mime',
            'modified',
            'name',
            'next' => 'compact',
            'niceSize',
            'parent' => 'compact',
            'options',
            'prev' => 'compact',
            'size',
            'template',
            'type',
            'url'
        ],
        'compact' => [
            'filename',
            'id',
            'link',
            'type',
            'url',
        ],
        'panel' => [
            'blueprint',
            'dimensions',
            'extension',
            'filename',
            'id',
            'link',
            'mime',
            'modified',
            'name',
            'nextWithTemplate' => 'compact',
            'niceSize',
            'options',
            'parent' => 'compact',
            'parents' => ['id', 'slug', 'title'],
            'prevWithTemplate' => 'compact',
            'template',
            'type',
            'url'
        ]
    ],
];
