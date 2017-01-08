/* Gulp setup for bespoke styling of message boxes -
   Bespoke styles/scripts will not work if you intend to re-install this module
   through composer.
----------------------------------------------------------------------------------------------------------------------*/

var gulp = require("gulp"),
	babel = require('gulp-babel'),
    rename = require("gulp-rename"),
    uglify = require ("gulp-uglify"),
	saveLicense = require('uglify-save-license'),
    autoPrefix = require("gulp-autoprefixer"),
    minifyCss = require("gulp-clean-css"),
    plumber = require("gulp-plumber"),
    sass = require("gulp-sass");

gulp.task("sass", function() {
  gulp.src("sass/styles.sass")
  .pipe(plumber())
  .pipe(sass())
  .pipe(autoPrefix({
    browsers: ['last 2 versions']
  }))
  .pipe(gulp.dest('css/dev'))
  .pipe(minifyCss())
  .pipe(gulp.dest('css'))
})

gulp.task("scripts", function() {
  gulp.src("javascript/src/scripts.js")
  .pipe(rename({suffix: '.min'}))
  .pipe(babel(
	  {presets: ['es2015']}
  ))
  .pipe(uglify({output: {comments: /^!|@preserve|@license|@cc_on/i}}))
  .pipe(gulp.dest('javascript'))
})

gulp.task('watch', function() {
  gulp.watch('javascript/src/scripts.js', ['scripts']);
  gulp.watch('sass/styles.sass', ['sass']);
})

gulp.task('default', ['sass', 'scripts', 'watch'])
