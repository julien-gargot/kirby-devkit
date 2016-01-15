/*

---------------------------------------
User settings
---------------------------------------

List all your JavaScript file in `userScripts` to define
their order of concatenation.

List all the JavaScript plugin file you are using in `pluginScripts`
to define their order of concatenation.

Managing more than one LESS/CSS is made with @imports in LESS.

*/

var pluginsScripts = [
  'bower_components/jquery/dist/jquery.js',
  'bower_components/bootstrap/dist/js/bootstrap.js'
];
var userScripts = [
  'assets/js/main.js'
];

/*

---------------------------------------
Gulp definition
---------------------------------------

By default you don’t have to configure anything to
make Gulp work.

gulp `default` task are “lint”, “css”, “script-plugins”, “scripts”.
gulp `dev-watch` task execute “lint” and “script-plugins” from `userScripts`
and “less” from `assets/less/*`.

*/

// Include gulp
var gulp = require('gulp');



// Include Our Plugins
var autoprefixer = require('gulp-autoprefixer');
var concat  = require('gulp-concat');
var jshint  = require('gulp-jshint');
var less    = require('gulp-less');
var nano    = require('gulp-cssnano');
var plumber = require('gulp-plumber');
var rename  = require('gulp-rename');
var uglify  = require('gulp-uglify');



// Compile our LESS
gulp.task('less', function() {
  return gulp.src( 'assets/less/main.less')
    .pipe(plumber())
    .pipe(less())
    .pipe(autoprefixer({
    	browsers: ['last 3 versions'],
    	cascade: false
    }))
    .pipe(gulp.dest('assets/css'));
});



// Prefix & Minify CSS
/*
 * NB: If CSS task does not contain LESS operations and that you execute
 * ('less', 'css'), CSS task finish before LESS, so it does not work include
 * less udpate. I do not understand why.
 */
gulp.task('css', function () {
  return gulp.src( 'assets/less/main.less')
    .pipe(plumber())
    .pipe(less())
    .pipe(autoprefixer({
			browsers: ['last 3 versions'],
			cascade: false
		}))
    .pipe(gulp.dest('assets/css'))
    .pipe(nano({discardComments: {removeAll: true}}))
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('assets/production'));
});



// Lint Task
gulp.task('lint', function() {
  return gulp.src( userScripts)
    .pipe(jshint())
    .pipe(jshint.reporter('default'));
});



// Concatenate JS plugin
gulp.task('script-plugins', function() {
  return gulp.src(pluginsScripts)
    .pipe(concat('plugins.js'))
    .pipe(gulp.dest('assets/js'));
});



// Concatenate JS plugin with user scripts and minify them.
gulp.task('scripts', function() {
  return gulp.src(['assets/js/plugins.js'].concat(userScripts))
    .pipe(concat('all.js'))
    .pipe(gulp.dest('assets/production'))
    .pipe(rename('all.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('assets/production'));
});



// Watch Files For Changes
gulp.task('dev-watch', function() {
  gulp.watch( userScripts, ['lint', 'script-plugins']);
  gulp.watch( 'assets/less/*.less', ['less']);
});



// Default Task
gulp.task('default', ['lint', 'css', 'script-plugins', 'scripts']);
