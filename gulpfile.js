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
  The task performs the following instructions: “_export-vars-to-kirby”, “_lint”,
  “_sass”, “_css” and “_scripts”.

- gulp `dev` runs only the essentials to create all files needed for
  development. The task performs the following instructions:
  “_export-vars-to-kirby”, “_lint” and “_sass”.

- gulp `dev-watch` task execute “export-vars-to-kirby”,
  run “_lint”, “_scripts” and “_sass” watching `assets/manifest.js`,
  run “_lint” watching `userScripts` and
  run “_sass” watching `userStyles`.

*/



// Helpers function
Array.prototype.unique = function() {
  return this.reduce(function(accum, current) {
    if (accum.indexOf(current) < 0) {
      accum.push(current);
    }
    return accum;
  }, []);
}



// Global variables
var vars = require("./assets/manifest.js");



// Include gulp
var gulp = require('gulp');



// Include Our Plugins
var gp_autoprefixer = require('gulp-autoprefixer');
var gp_browserSync  = require('browser-sync').create();
var gp_concat       = require('gulp-concat');
var gp_concatCss    = require('gulp-concat-css');
var gp_fs           = require('fs');
var gp_jshint       = require('gulp-jshint');
var gp_nano         = require('gulp-cssnano');
var gp_plumber      = require('gulp-plumber');
var gp_rename       = require('gulp-rename');
var gp_sass         = require('gulp-sass')(require('sass'));
var gp_uglify       = require('gulp-uglify');



// Compile our SCSS
gulp.task('_sass', function() {
  return gulp.src( vars.userStyles )
    .pipe(gp_plumber({
      errorHandler: function (err) {
        console.error(err.formatted);
        this.emit('end');
      }
    }))
    .pipe(gp_sass())
    .pipe(gp_autoprefixer({
    	cascade: false
    }))
    .pipe(gulp.dest('assets/css'))
    .pipe(gp_browserSync.stream());
});



// Prefix & Minify CSS
gulp.task('_css', function (done) {

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
gulp.task('_lint', function() {
  return gulp.src( vars.userScripts )
    .pipe(gp_jshint())
    .pipe(gp_jshint.reporter('default'));
});



// Concatenate JS plugins with user scripts and minify them.
gulp.task('_scripts', function (done) {
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
gulp.task('_export-vars-to-kirby', function(done){
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
  var destination = 'site/plugins/assets';
  var assets  = "<?php\n";
      assets += "# Automatically generated file by Gulp for kirby-devkit; DO NOT EDIT.\n";
      assets += "Kirby::plugin('kirby-devkit/assets', [\n";
      assets += "  'options' => [\n";
      assets += "    'styles' => " + JSON.stringify(styles) + ",\n";
      assets += "    'scripts' => " + JSON.stringify(scripts) + ",\n";
      assets += "  ]\n";
      assets += "]);";

  if(!gp_fs.existsSync(destination)) {
    gp_fs.mkdirSync(destination);
  }
  gp_fs.writeFileSync(destination + '/index.php', assets );
  done();
});



// Watch Files For Changes
gulp.task('_watch', function() {
  var scripts_folders = vars.userScripts.map(function(path){
    return path.replace(
      /^((.+)\/)?((.+?)(\.[^.]*$|$))$/g,
      "$1**"
    );
  }).unique();
  var styles_folders = vars.userStyles.map(function(path){
    return path.replace(
      /^((.+)\/)?((.+?)(\.[^.]*$|$))$/g,
      "$1**"
    );
  }).unique();
  gulp.watch( './assets/manifest.js', gulp.series('_export-vars-to-kirby', '_lint', '_sass'));
  gulp.watch( scripts_folders, gulp.series('_lint'));
  gulp.watch( styles_folders, gulp.series('_sass'));
});



// Watch Files For Changes
gulp.task('dev-watch', gulp.series('_export-vars-to-kirby', '_watch'));

// Dev Task
gulp.task('dev', gulp.series('_export-vars-to-kirby', '_lint', '_sass'));

// Run every tasks in order to build files for production
gulp.task('prod', gulp.series('_export-vars-to-kirby', '_lint', '_sass', '_css', '_scripts'));

// Default Task
gulp.task('default', gulp.series('prod'));
