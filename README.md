# Kirby Dev Kit v4

This repo is a starting point to use Kirby 4 with Gulp and Npm and a minimal Bootstrap 5. It is made for developers to bootstrap their own projects quickly.

## Requirements

You will need Npm and GIT (for the Kirby submodules) to use this project.

## Setup a new project

1. clone this repo :
  ```
  git clone https://github.com/julien-gargot/kirby-devkit.git path/to/your-project
  cd path/to/your-project
  git submodule update --init
  ```

2. install Npm :
  ```
  npm ci
  ```

## Configure your server and site settings

1. rename the file in `site/config/config.localhost.php` to your local development site URL [check out Multi-environment setup](https://getkirby.com/docs/guide/configuration#multi-environment-setup). The `environment` variable is used to load minified or unminified CSS/JS versions (checkout `snippets/header.php` and `snippets/footer.php`).

2. setup your localDevUrl in `assets/manifest.js` to be able to use live reload (see Compile Assets).

## Compile Assets

1. to compile all files, for **development** and **production** :
  ```
  gulp
  ```

2. To make it faster, while developing, you can watch for changes to CSS and JS files in the assets folder. This task only compiles **development** files.
  ```
  gulp watch
  ```
  for dev purpose you can also use:
  ```
  gulp watch --no-purge
  ```

**NB** *As a CSS purge is configured globally by default, if you want to save particular class (created by JS, load from external API, etc.), the easiest way is to create a `site/snippets/purge.php` with your custom HTML.*
