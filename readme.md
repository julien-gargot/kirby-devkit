# Kirby boilerplate

This repo is a starting point to use Kirby with Gulp and Bower. It is made for developers to bootstrap their own projects quickly.

## Requirements

You need NPM, Bower and GIT (for the Kirby submodules) to use this project.

## Setup a new project

1. clone this repo :
  ```
  git clone --recursive https://github.com/julien-gargot/kirby-devkit.git path/to/your-project
  cd path/to/your-project
  ```

2. install NPM and Bower :
  ```
  npm install
  bower install
  ```

3. to compile all files, for **development** and **production** :
  ```
  gulp
  ```

  To make it faster, while developing, you can watch for changes to CSS and JS files in the assets folder. This task only compiles **development** files.
  ```
  gulp dev-watch
  ```

## Configure with your server/site settings

1. rename the file in `site/config/config.{localhost}.php` to your local development site URL [check out Multi-environment setup](http://getkirby.com/docs/advanced/options). The `environment` variable is used to load minified or unminified CSS/JS versions (checkout `snippets/header.php` and `snippets/footer.php`).
2. to enable browser sync (live reloading, remote debugging, and a few other nice features), set the `localDevUrl` variable to the URL of your site at the top of `gulpfile.js`.


## License

Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)

This is a human-readable summary of (and not a substitute for) the [license](http://creativecommons.org/licenses/by-sa/4.0/legalcode).
Disclaimer
This license is acceptable for Free Cultural Works.

### You are free to:

**Share** — copy and redistribute the material in any medium or format

**Adapt** — remix, transform, and build upon the material for any purpose, even commercially.

The licensor cannot revoke these freedoms as long as you follow the license terms.

### Under the following terms:

**Attribution** — You must give *appropriate credit*, provide a link to the license, and *indicate if changes were made*. You may do so in any reasonable manner, but not in any way that suggests the licensor endorses you or your use.

**ShareAlike** — If you remix, transform, or build upon the material, you must distribute your contributions under the *same license* as the original.

**No additional restrictions** — You may not apply legal terms or *technological measures* that legally restrict others from doing anything the license permits.

### Notices:

You do not have to comply with the license for elements of the material in the public domain or where your use is permitted by an applicable exception or limitation.
No warranties are given. The license may not give you all of the permissions necessary for your intended use. For example, other rights such as publicity, privacy, or moral rights may limit how you use the material.

---

NOTE: to use Kirby on a public site, you need to [purchase a license](http://getkirby.com/buy).
