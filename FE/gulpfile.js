'use strict';

const gulp = require('gulp'),
	plumber = require('gulp-plumber'),
	notify = require('gulp-notify'),
	watch = require('gulp-watch'),
	sass = require('gulp-sass'),
    svgSprite = require('gulp-svg-sprite'),
	cssmin = require('gulp-cssmin'),
	gcmq = require('gulp-group-css-media-queries');

const onError = function (err) {
	notify.onError({
		title: "Gulp",
		subtitle: "Failure!",
		message: "Error: <%= error.message %>",
		sound: "Beep"
	})(err);

	this.emit('end');
};

const path = {
	build: {
		css: '../public/assets/backend/css/',
		svg: '../public/assets/backend/icons/',
	},
	src: {
		style: 'src/style/main.scss',
        svg: 'src/svg/*.svg',
	},
	watch: {
		style: 'src/style/**/*.scss',
        svg: 'src/svg/*.svg',
	}
};

const styles = function() {
	return gulp.src(path.src.style)
		.pipe(plumber({errorHandler: onError}))
		.pipe(sass())
		.pipe(gcmq())
		.pipe(cssmin())
		.pipe(gulp.dest(path.build.css))
};

const svg = function() {
    return gulp.src(path.src.svg)
        .pipe(svgSprite({mode: {stack: {sprite: '../sprite.svg'}}}))
        .pipe(gulp.dest(path.build.svg));
};


const watchTask = function() {
	gulp.watch(path.watch.style, styles);
	gulp.watch(path.watch.svg, svg);
};

const build = gulp.series(styles, svg, watchTask);

exports.styles = styles;
exports.watch = watch;
exports.build = build;

/*
 * Define default task that can be called by just running `gulp` from cli
 */

exports.default = build;
