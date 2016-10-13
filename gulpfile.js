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
  “sass”, “css”, “script-plugins” and “scripts”.

- gulp `dev` runs only the essentials to create all files needed for
  development. The task performs the following instructions:
  “export-vars-to-kirby”, “lint”, “script-plugins” and “sass”.

- gulp `dev-watch` task execute “export-vars-to-kirby”,
  run “lint”, “script-plugins” and “sass” watching `assets/manifest.js`,
  run “lint” and “script-plugins” watching `userScripts` and
  run “sass” watching `userStyles`.

*/

// Include gulp
var gulp = require('gulp');



// Include Our Plugins
var vars = require("./assets/manifest.js");
var autoprefixer = require('gulp-autoprefixer');
var browserSync  = require('browser-sync').create();
var concat       = require('gulp-concat');
var fs           = require('fs');
var jshint       = require('gulp-jshint');
var nano         = require('gulp-cssnano');
var plumber      = require('gulp-plumber');
var rename       = require('gulp-rename');
var sass         = require('gulp-sass');
var uglify       = require('gulp-uglify');



// Compile our SCSS
gulp.task('sass', function() {
  return gulp.src( vars.userStyles )
    .pipe(plumber({
      errorHandler: function (err) {
        console.log(err.message);
        this.emit('end');
      }
    }))
    .pipe(sass())
    .pipe(autoprefixer({
    	browsers: ['last 3 versions'],
    	cascade: false
    }))
    .pipe(gulp.dest('assets/css'))
    .pipe(browserSync.stream());
});



// Prefix & Minify CSS
gulp.task('css', ['sass'], function (done) {

  // keep CSS order
  var styles = vars.userStyles.map(function(path){
    return path.replace(
      /^((.+)\/)?((.+?)(\.[^.]*$|$))$/g,
      "assets/css/$4.css"
    );
  });

  return gulp.src( styles )
    .pipe(concat('all.css'))
    .pipe(gulp.dest('assets/production'))
    .pipe(nano({discardComments: {removeAll: true}, autoprefixer: false}))
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('assets/production'));
});



// Lint Task
gulp.task('lint', function() {
  return gulp.src( vars.userScripts )
    .pipe(jshint())
    .pipe(jshint.reporter('default'));
});



// Concatenate JS plugin
gulp.task('script-plugins', function() {
  return gulp.src( vars.pluginScripts )
    .pipe(concat('plugins.js'))
    .pipe(gulp.dest('assets/js'))
    .pipe(browserSync.stream());
});



// Concatenate JS plugin with user scripts and minify them.
gulp.task('scripts', ['script-plugins'], function (done) {
  return gulp.src(['assets/js/plugins.js'].concat( vars.userScripts ))
    .pipe(concat('all.js'))
    .pipe(gulp.dest('assets/production'))
    .pipe(rename('all.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('assets/production'));
});



// Export `manifest.js` vars to PHP through a kirby plugin.
// cf. `snippets/header` and `snippets/footer`
gulp.task('export-vars-to-kirby', function() {

  // get CSS
  var styles = vars.userStyles.map(function(path){
    return path.replace(
      /^((.+)\/)?((.+?)(\.[^.]*$|$))$/g,
      "assets/css/$4.css"
    );
  });

  // get JS
  var scripts = vars.pluginScripts.concat( vars.userScripts );

  // Create a Kirby plugin that defines asset vars
  var assets  = "<?php\n";
      assets += "# Automatically generated file by Gulp for kirby-devkit; DO NOT EDIT.\n";
      assets += "c::set('styles', " + JSON.stringify(styles) + ");\n";
      assets += "c::set('scripts', " + JSON.stringify(scripts) + ");\n";

  fs.writeFileSync('site/plugins/assets.php', assets );

});



// Live reload sync on every screen connect to localhost
gulp.task('init-live-reload', function() {
  browserSync.init({
    proxy: localDevUrl,
    notify: false,
    snippetOptions: {
      ignorePaths: ['panel/**', 'site/accounts/**']
    },
  });
});



// Watch Files For Changes
gulp.task('dev-watch', ['export-vars-to-kirby'], function() {
  gulp.watch( './assets/manifest.js', ['export-vars-to-kirby', 'lint', 'script-plugins', 'sass']);
  gulp.watch( vars.userScripts, ['lint', 'script-plugins']);
  // watch style folders instead of files
  var styles = vars.userStyles.map(function(path){
    return path.replace(
      /^((.+)\/)?((.+?)(\.[^.]*$|$))$/g,
      "$1*"
    );
  }).unique();
  gulp.watch( styles, ['sass']);
});

// Watch Files For Changes with live reload sync on every screen connect to localhost.
gulp.task('dev-watch-sync', ['init-live-reload', 'dev-watch']);

// Dev Task
gulp.task('dev', ['export-vars-to-kirby', 'lint', 'script-plugins', 'sass']);

// Run every tasks in order to build files for production
gulp.task('prod', ['export-vars-to-kirby', 'lint', 'sass', 'css', 'script-plugins', 'scripts']);

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
