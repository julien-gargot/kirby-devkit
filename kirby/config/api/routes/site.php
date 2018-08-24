<?php

/**
 * Site Routes
 */
return [

    [
        'pattern' => 'site',
        'action'  => function () {
            return $this->site();
        }
    ],
    [
        'pattern' => 'site',
        'method'  => 'PATCH',
        'action'  => function () {
            return $this->site()->update($this->requestBody());
        }
    ],
    [
        'pattern' => 'site/children',
        'method'  => 'GET',
        'action'  => function () {
            return $this->site()->children();
        }
    ],
    [
        'pattern' => 'site/children',
        'method'  => 'POST',
        'action'  => function () {
            return $this->site()->createChild($this->requestBody());
        }
    ],
    [
        'pattern' => 'site/children/search',
        'method'  => 'POST',
        'action'  => function () {
            return $this->site()->children()->query($this->requestBody());
        }
    ],
    [
        'pattern' => 'site/blueprint',
        'method'  => 'GET',
        'action'  => function () {
            return $this->site()->blueprint();
        }
    ],
    [
        'pattern' => 'site/blueprints',
        'method'  => 'GET',
        'action'  => function () {
            return $this->site()->blueprints($this->requestQuery('section'));
        }
    ],
    [
        'pattern' => 'site/files',
        'method'  => 'GET',
        'action'  => function () {
            return $this->site()->files();
        }
    ],
    [
        'pattern' => 'site/files',
        'method'  => 'POST',
        'action'  => function () {
            return $this->upload(function ($source, $filename) {
                return $this->site()->createFile([
                    'source'   => $source,
                    'template' => $this->requestBody('template'),
                    'filename' => $filename
                ]);
            });
        }
    ],
    [
        'pattern' => 'site/files/search',
        'method'  => 'POST',
        'action'  => function () {
            return $this->site()->files()->query($this->requestBody());
        }
    ],
    [
        'pattern' => 'site/files/sort',
        'method'  => 'PATCH',
        'action'  => function () {
            return $this->site()->files()->changeSort($this->requestBody('files'));
        }
    ],
    [
        'pattern' => 'site/files/(:any)',
        'method'  => 'GET',
        'action'  => function (string $filename) {
            return $this->file(null, $filename);
        }
    ],
    [
        'pattern' => 'site/files/(:any)',
        'method'  => 'POST',
        'action'  => function (string $filename) {
            return $this->upload(function ($source) use ($filename) {
                return $this->file(null, $filename)->replace($source);
            });
        }
    ],
    [
        'pattern' => 'site/files/(:any)',
        'method'  => 'PATCH',
        'action'  => function (string $filename) {
            return $this->file(null, $filename)->update($this->requestBody());
        }
    ],
    [
        'pattern' => 'site/files/(:any)',
        'method'  => 'DELETE',
        'action'  => function (string $filename) {
            return $this->file(null, $filename)->delete();
        }
    ],
    [
        'pattern' => 'site/files/(:any)/options',
        'method'  => 'GET',
        'action'  => function (string $filename) {
            return $this->file(null, $filename)->permissions()->toArray();
        }
    ],
    [
        'pattern' => 'site/files/(:any)/rename',
        'method'  => 'PATCH',
        'action'  => function (string $filename) {
            return $this->file(null, $filename)->changeName($this->requestBody('name'));
        }
    ],
    [
        'pattern' => 'site/files/(:any)/sections/(:any)',
        'method'  => 'GET',
        'action'  => function (string $filename, string $sectionName) {
            if ($section = $this->file(null, $filename)->blueprint()->section($sectionName)) {
                return $section->toResponse();
            }
        }
    ],
    [
        'pattern' => 'site/options',
        'method'  => 'GET',
        'action'  => function () {
            return $this->site()->permissions()->toArray();
        }
    ],
    [
        'pattern' => 'site/title',
        'method'  => 'PATCH',
        'action'  => function () {
            return $this->site()->changeTitle($this->requestBody('title'));
        }
    ],
    [
        'pattern' => 'site/sections/(:any)',
        'method'  => 'GET',
        'action'  => function (string $sectionName) {
            if ($section = $this->site()->blueprint()->section($sectionName)) {
                return $section->toResponse();
            }
        }
    ]

];
