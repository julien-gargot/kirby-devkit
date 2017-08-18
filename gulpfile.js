/*

---------------------------------------
User settings
---------------------------------------

User settings are set in ./assets/manifest.js

*/



/*

---------------------------------------
Gulp definition
---------------------------------------

By default you don’t have to configure anything to
make Gulp work.

- gulp `default` is `prod`.

- gulp `prod` runs everything to create all files needed in production.
  The task performs the following instructions: “export-vars-to-kirby”, “lint”,
  “sass”, “css” and “scripts”.

- gulp `dev` runs only the essentials to create all files needed for
  development. The task performs the following instructions:
  “export-vars-to-kirby”, “lint” and “sass”.

- gulp `dev-watch` task execute “export-vars-to-kirby”,
  run “lint”, “script-plugins” and “sass” watching `assets/manifest.js`,
  run “lint” watching `userScripts` and
  run “sass” watching `userStyles`.

*/

// Include gulp
var gulp = require('gulp');



// Include Our Plugins
var vars = require("./assets/manifest.js");
var gp_autoprefixer = require('gulp-autoprefixer');
var gp_browserSync  = require('browser-sync').create();
var gp_concat       = require('gulp-concat');
var gp_concatCss    = require('gulp-concat-css');
var gp_fs           = require('fs');
var gp_jshint       = require('gulp-jshint');
var gp_nano         = require('gulp-cssnano');
var gp_plumber      = require('gulp-plumber');
var gp_rename       = require('gulp-rename');
var gp_sass         = require('gulp-sass');
var gp_uglify       = require('gulp-uglify');



// Compile our SCSS
gulp.task('sass', function() {
  return gulp.src( vars.userStyles )
    .pipe(gp_plumber({
      errorHandler: function (err) {
        console.error(err.formatted);
        this.emit('end');
      }
    }))
    .pipe(gp_sass())
    .pipe(gp_autoprefixer({
    	browsers: ['last 3 versions'],
    	cascade: false
    }))
    .pipe(gulp.dest('assets/css'))
    .pipe(gp_browserSync.stream());
});



// Prefix & Minify CSS
gulp.task('css', ['sass'], function (done) {

  // get CSS (ordered)
  var styles = vars.userStyles.map(function(path){
    return path.replace(
      /^((.+)\/)?((.+?)(\.[^.]*$|$))$/g,
      "assets/css/$4.css"
    );
  });
  styles = vars.pluginStyles.concat( styles );

  return gulp.src( styles, { base: 'assets/production' } )
    .pipe(gp_concatCss('all.min.css', {
      inlineImports: false,
      rebaseUrls: true
    }))
    .pipe(gulp.dest('assets/production'))
    .pipe(gp_nano({discardComments: {removeAll: true}, autoprefixer: false}))
    .pipe(gulp.dest('assets/production'));
});



// Lint Task
gulp.task('lint', function() {
  return gulp.src( vars.userScripts )
    .pipe(gp_jshint())
    .pipe(gp_jshint.reporter('default'));
});



// Concatenate JS plugins with user scripts and minify them.
gulp.task('scripts', function (done) {
  var scripts = vars.pluginScripts.concat( vars.userScripts );
  return gulp.src( scripts )
    .pipe(gp_concat('all.min.js'))
    .pipe(gp_uglify({
      compress: {
        drop_debugger : true,
        drop_console : true
      }
    }))
    .pipe(gulp.dest('assets/production'));
});



// Export `manifest.js` vars to PHP through a kirby plugin.
// cf. `snippets/header` and `snippets/footer`
gulp.task('export-vars-to-kirby', function() {

  // get CSS (ordered)
  var styles = vars.userStyles.map(function(path){
    return path.replace(
      /^((.+)\/)?((.+?)(\.[^.]*$|$))$/g,
      "assets/css/$4.css"
    );
  });
  styles = vars.pluginStyles.concat( styles );

  // get JS
  var scripts = vars.pluginScripts.concat( vars.userScripts );

  // Create a Kirby plugin that defines asset vars
  var assets  = "<?php\n";
      assets += "# Automatically generated file by Gulp for kirby-devkit; DO NOT EDIT.\n";
      assets += "c::set('styles', " + JSON.stringify(styles) + ");\n";
      assets += "c::set('scripts', " + JSON.stringify(scripts) + ");\n";

  gp_fs.writeFileSync('site/plugins/assets.php', assets );

});



// Live reload sync on every screen connect to localhost
gulp.task('init-live-reload', function() {
  gp_browserSync.init({
    proxy: vars.localDevUrl,
    notify: false,
    snippetOptions: {
      ignorePaths: ['panel/**', 'site/accounts/**']
    },
  });
});



// Watch Files For Changes
gulp.task('dev-watch', ['export-vars-to-kirby'], function() {
  gulp.watch( './assets/manifest.js', ['export-vars-to-kirby', 'lint', 'sass']);
  gulp.watch( vars.userScripts, ['lint']);
  // watch style folders instead of files
  var styles = vars.userStyles.map(function(path){
    var response = path.replace(
      /^((.+)\/)?((.+?)(\.[^.]*$|$))$/g,
      "$1**"
    );
    return response;
  }).unique();
  gulp.watch( styles, ['sass']);
});

// Watch Files For Changes with live reload sync on every screen connect to localhost.
gulp.task('dev-watch-sync', ['init-live-reload', 'dev-watch']);

// Dev Task
gulp.task('dev', ['export-vars-to-kirby', 'lint', 'sass']);

// Run every tasks in order to build files for production
gulp.task('prod', ['export-vars-to-kirby', 'lint', 'sass', 'css', 'scripts']);

// Default Task
gulp.task('default', ['prod']);



// Helpers function
Array.prototype.unique = function() {
  return this.reduce(function(accum, current) {
    if (accum.indexOf(current) < 0) {
      accum.push(current);
    }
    return accum;
  }, []);
}
