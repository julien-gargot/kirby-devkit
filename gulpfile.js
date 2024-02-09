const gulp = require('gulp');
const argv = require('minimist')(process.argv.slice(2)); // Parse command-line arguments
const autoprefixer = require('gulp-autoprefixer');
const browserSync = require('browser-sync').create();
const cleanCSS = require('gulp-clean-css');
const concat = require('gulp-concat');
const concatCss = require('gulp-concat-css');
const fs = require('fs');
const jshint = require('gulp-jshint');
const purgecss = require('gulp-purgecss');
const sass = require('gulp-sass')(require('sass'));
const uglify = require('gulp-uglify');

const vars = require('./assets/manifest.js');

// Define your source and destination paths
const paths = {
  url: vars.localDevUrl,
  styles: {
    src: vars.userStyles,
    build: 'assets/build/css',
    dest: 'assets/dist/css',
  },
  scripts: {
    src: vars.userScripts,
    build: 'assets/build/js',
    dest: 'assets/dist/js',
  },
  pluginStyles: {
    src: vars.pluginStyles,
  },
  pluginScripts: {
    src: vars.pluginScripts,
  }
};

// Initialize Browsersync
function server(done) {
  browserSync.init({
    proxy: paths.url,
    notify: false,
    snippetOptions: {
      ignorePaths: ['panel/**', 'site/accounts/**']
    },
  });
  done();
}

// Reload the browser using Browsersync
function reload(done) {
  browserSync.reload();
  done();
}

// Compile SCSS, add prefixes styles
function styles() {
  return gulp
    .src([...paths.styles.src, ...paths.pluginStyles.src])
    .pipe(sass().on('error', sass.logError))
    .pipe(autoprefixer())
    .pipe(gulp.dest(paths.styles.build))
}

// Purge unused CSS
function purge() {
  return gulp
    .src([`${paths.styles.build}/*.css`])
    .pipe(
      purgecss({
        content: ['site/**/*.php'],
        variables: true,
        safelist: {
          deep: [/svg$/]
        }
      })
    )
    .pipe(gulp.dest(paths.styles.build));
}

// Concat and minify CSS
function minify() {
  return gulp
    .src([`${paths.styles.build}/*.css`])
    .pipe(concatCss('styles.min.css', {
      inlineImports: false,
      rebaseUrls: false
    }))
    .pipe(cleanCSS())
    .pipe(gulp.dest(paths.styles.dest));
}

// Lint JS files
function lint() {
  return gulp
    .src(paths.scripts.src)
    .pipe(jshint({
      esversion: 6
    }))
    .pipe(jshint.reporter('default'));
}

// Concatenate and minify JS files for production
function scripts() {
  return gulp
    .src([...paths.scripts.src, ...paths.pluginScripts.src]) // Include your scripts and plugin scripts
    .pipe(concat('app.min.js'))
    .pipe(uglify({
      compress: {
        drop_debugger : true,
        drop_console : true
      }
    }))
    .pipe(gulp.dest(paths.scripts.dest));
}

// Define a watch task for development
function watch() {
  const styleFolders = [...paths.styles.src, ...paths.pluginStyles.src]
    .map(file => `${file.split('/').slice(0, -1).join('/')}/**/*.scss`);
  const tasks = ['styles'];
  if (argv['purge'] !== false) {
    tasks.push('purge');
  }
  gulp.watch(styleFolders, gulp.series(...tasks, reload));
  gulp.watch([...paths.scripts.src, ...paths.pluginScripts.src], gulp.series(scripts, reload));
}

function exportVarsToKirby(done) {
  const styles = [];
  const destination = 'site/plugins/assets';

  gulp.src([`${paths.styles.build}/*.css`])
    .on('data', file => {
      styles.push(file.path.replace(__dirname, ''));
    })
    .on('end', () => {

      if (!fs.existsSync(destination)) {
        fs.mkdirSync(destination);
      }

      const currentTimeInSeconds = Math.floor(Date.now() / 1000);
      const assets = `<?php
# Automatically generated file by Gulp for kirby-devkit; DO NOT EDIT.
Kirby::plugin('kirby-devkit/assets', [
  'options' => [
    'styles' => ${JSON.stringify(styles)},
    'scripts' => ${JSON.stringify([...paths.pluginScripts.src, ...paths.scripts.src])},
    'version' => ${JSON.stringify(currentTimeInSeconds)}
  ]
]);`;

      fs.writeFileSync(`${destination}/index.php`, assets);
      done();
    });
}

// Define the default task
const build = gulp.series(exportVarsToKirby, styles, purge, minify, lint, scripts);

exports.styles = styles;
exports.purge = purge;
exports.scripts = scripts;
exports.exportVarsToKirby = exportVarsToKirby;
exports.watch = gulp.series(exportVarsToKirby, server, watch);
exports.default = build;
