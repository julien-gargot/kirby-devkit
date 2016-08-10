/*

---------------------------------------
User settings
---------------------------------------

List all your JavaScript file in `userScripts` to define
their order of concatenation.

List all the JavaScript plugin files you are using in `pluginScripts`
to define their loading order.

Name your LESS config file to load.
Managing more than one LESS/CSS should be from @imports in LESS.

To enable automatic reloading on .js and .less files compilation,
as well as other niceties from [browser sync](https://www.browsersync.io/)
write your local dev url in the localDevUrl variable.

*/
module.exports.pluginScripts = [
  'bower_components/jquery/dist/jquery.js',
  'bower_components/bootstrap/dist/js/bootstrap.js'
];
module.exports.userScripts = [
  'assets/js/main.js'
];
module.exports.userStyles = [
  'assets/less/main.less'
];
module.exports.localDevUrl = '';
