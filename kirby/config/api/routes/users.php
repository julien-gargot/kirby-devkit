<?php

/**
 * User Routes
 */
return [

    [
        'pattern' => 'users',
        'method'  => 'GET',
        'action'  => function () {
            return $this->users();
        }
    ],
    [
        'pattern' => 'users',
        'method'  => 'POST',
        'action'  => function () {
            return $this->users()->create($this->requestBody());
        }
    ],
    [
        'pattern' => 'users/search',
        'method'  => 'POST',
        'action'  => function () {
            return $this->users()->query($this->requestBody());
        }
    ],
    [
        'pattern' => 'users/(:any)',
        'method'  => 'GET',
        'action'  => function (string $id) {
            return $this->user($id);
        }
    ],
    [
        'pattern' => 'users/(:any)',
        'method'  => 'PATCH',
        'action'  => function (string $id) {
            return $this->user($id)->update($this->requestBody());
        }
    ],
    [
        'pattern' => 'users/(:any)',
        'method'  => 'DELETE',
        'action'  => function (string $id) {
            return $this->user($id)->delete();
        }
    ],
    [
        'pattern' => 'users/(:any)/avatar',
        'method'  => 'GET',
        'action'  => function (string $id) {
            return $this->user($id)->avatar();
        }
    ],
    [
        'pattern' => 'users/(:any)/avatar',
        'method'  => 'POST',
        'action'  => function (string $id) {
            return $this->upload(function ($source, $filename) use ($id) {
                return $this->user($id)->avatar()->replace($source);
            }, $single = true);
        }
    ],
    [
        'pattern' => 'users/(:any)/avatar',
        'method'  => 'DELETE',
        'action'  => function (string $id) {
            return $this->user($id)->avatar()->delete();
        }
    ],
    [
        'pattern' => 'users/(:any)/blueprint',
        'method'  => 'GET',
        'action'  => function (string $id) {
            return $this->user($id)->blueprint();
        }
    ],
    [
        'pattern' => 'users/(:any)/email',
        'method'  => 'PATCH',
        'action'  => function (string $id) {
            return $this->user($id)->changeEmail($this->requestBody('email'));
        }
    ],
    [
        'pattern' => 'users/(:any)/language',
        'method'  => 'PATCH',
        'action'  => function (string $id) {
            return $this->user($id)->changeLanguage($this->requestBody('language'));
        }
    ],
    [
        'pattern' => 'users/(:any)/name',
        'method'  => 'PATCH',
        'action'  => function (string $id) {
            return $this->user($id)->changeName($this->requestBody('name'));
        }
    ],
    [
        'pattern' => 'users/(:any)/options',
        'method'  => 'GET',
        'action'  => function (string $id) {
            return $this->user($id)->blueprint()->options()->toArray();
        }
    ],
    [
        'pattern' => 'users/(:any)/password',
        'method'  => 'PATCH',
        'action'  => function (string $id) {
            return $this->user($id)->changePassword($this->requestBody('password'));
        }
    ],
    [
        'pattern' => 'users/(:any)/role',
        'method'  => 'PATCH',
        'action'  => function (string $id) {
            return $this->user($id)->changeRole($this->requestBody('role'));
        }
    ],
    [
        'pattern' => 'users/(:any)/(:any)/(:all?)',
        'method'  => 'ALL',
        'action'  => function (string $id, string $sectionName, string $path = '') {
            return $this->user($id)->blueprint()->section($sectionName)->apiCall($this, $path);
        }
    ]

];