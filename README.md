# Kirby Dev Kit v3

This repo is a starting point to use Kirby 3 with Gulp and Npm. It is made for developers to bootstrap their own projects quickly.

## Requirements

You will need Npm and GIT (for the Kirby submodules) to use this project.

## Setup a new project

1. clone this repo :
  ```
  git clone -b v3 https://github.com/julien-gargot/kirby-devkit.git path/to/your-project
  cd path/to/your-project
  git submodule update --init
  ```

2. install Npm :
  ```
  npm ci
  ```

3. to compile all files, for **development** and **production** :
  ```
  gulp
  ```

  To make it faster, while developing, you can watch for changes to CSS and JS files in the assets folder. This task only compiles **development** files.
  ```
  gulp dev-watch
  ```

  ~Same as `gulp dev-watch` with live reload.~ _Not tested after gulp update (7/24/23)_
  ```
  #gulp dev-watch-sync
  ```
~~~

## Configure with your server/site settings

1. rename the file in `site/config/config.localhost.php` to your local development site URL [check out Multi-environment setup](http://getkirby.com/docs/advanced/options). The `environment` variable is used to load minified or unminified CSS/JS versions (checkout `snippets/header.php` and `snippets/footer.php`).
2. to be able to use browser sync (live reloading, remote debugging, and a few other nice features), set the `localDevUrl` variable to the URL of your site at the top of `gulpfile.js`.
