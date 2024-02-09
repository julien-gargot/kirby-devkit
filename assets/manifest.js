/*

---------------------------------------
User settings for Gulp
---------------------------------------

*/

/* List all the JavaScript plugin files you are using in `pluginScripts`
 * to define their loading order.
 */
module.exports.pluginScripts = [
  'node_modules/@popperjs/core/dist/umd/popper.js',
  'node_modules/bootstrap/dist/js/bootstrap.js',
];

/* List all the Styles plugin files you are using in `pluginStyles`
 * to define their loading order.
 */
module.exports.pluginStyles = [
];

/* List all your JavaScript file in `userScripts` to define
 * their order of concatenation.
 */
module.exports.userScripts = [
  'assets/src/js/main.js'
];

/* Name your SCSS config file to load.
 * Managing more than one SCSS/CSS should be from @imports in SCSS.
 */
module.exports.userStyles = [
  'assets/src/scss/main.scss'
];

/* To enable automatic reloading on .js and .scss files compilation,
 * as well as other niceties from [browser sync](https://www.browsersync.io/)
 * write your local dev url in the localDevUrl variable.
 */
module.exports.localDevUrl = 'http://dev.example.com/';
