/**
 * Imports for Gulp
 */
var gulp = require('gulp');
var bower = require('gulp-bower');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var autoPrefixer = require('gulp-autoprefixer');
var sassDoc = require('sassdoc');

// configuration
var settings = {
    bower: './bower_components',
    sass: {
        input: ['./scss/**/*.scss', './scss/vendors/**/**/*.scss'],
        output: './css',
        sourcemaps: './scss/maps',
        vendor: './scss/vendors',
        autoPrefixer: {
            browsers: ['last 2 versions', '> 5%', 'Firefox ESR']
        },
        options: {
            errLogToConsole: true,
            outputStyle: 'compressed' //compressed|expanded
        },
        sassDoc: {
            dest: './sass-doc'
        }
    },
    javascript: {
        modules: ['bootstrap', 'jquery', 'tether'],
        source: [
            'dist/js/**/*.min.js', 'dist/js/**/*.min.map',
            'dist/**/*.min.map', 'dist/**/*.min.js'
        ],
        destination: './js/vendor'
    }
};

// load javascript libraries
gulp.task('javascript', ['bower'], function () {
    var cfg = settings.javascript;
    var modules = settings.javascript.modules;
    for (var i=0; i<modules.length; i++) {
        var sources = [];
        for (var j=0; j<cfg.source.length; j++) {
            sources.push(settings.bower + '/' + modules[i] +'/'+cfg.source[j]);
        }
        gulp.src(sources)
            .pipe(gulp.dest(cfg.destination+'/'+modules[i]));
    }

    // bootstrap file input
    gulp.src(settings.bower + '/bootstrap-fileinput/js/**/*.js')
        .pipe(gulp.dest(cfg.destination + '/bootstrap-fileinput/js'));
    gulp.src(settings.bower + '/bootstrap-fileinput/themes/**/*.js')
        .pipe(gulp.dest(cfg.destination + '/bootstrap-fileinput/themes'))
});

// Load bootstrap sass files
gulp.task('bootstrap-sass', ['bower'], function () {
    gulp.src(settings.bower + '/bootstrap-fileinput/sass/**/*.scss')
        .pipe(gulp.dest(settings.sass.vendor + '/bootstrap-file-input'));
    return gulp.src(settings.bower + '/bootstrap/scss/**/*.scss')
        .pipe(gulp.dest(settings.sass.vendor+'/bootstrap'));
});

gulp.task('font-awesome', ['bower'], function () {
    gulp.src(settings.bower+'/font-awesome/fonts/**/*.*')
        .pipe(gulp.dest('./fonts'));
    gulp.src(settings.bower+'/font-awesome/scss/**/*.scss')
        .pipe(gulp.dest('./scss/vendors/font-awesome'));
});

// Compiling sass
gulp.task('sass', function () {
    return gulp
        .src(settings.sass.input)
        .pipe(sassDoc(settings.sass.sassDoc))
        .pipe(sourcemaps.init())
        .pipe(sass(settings.sass.options).on('error', sass.logError))
        .pipe(autoPrefixer(settings.sass.autoPrefixer))
        .pipe(sourcemaps.write(settings.sass.sourcemaps))
        .pipe(gulp.dest(settings.sass.output))
        // Release the pressure back and trigger flowing mode (drain)
        // See: http://sassdoc.com/gulp/#drain-event
        .resume();
});

// Watch the SASS source folder for changes,
// and run `sass` task when something happens
gulp.task('watch', function() {
    return gulp
        .watch(settings.sass.input, ['sass'])
        .on('change', function(event) {
            console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
        });
});

// Bower update
gulp.task('bower', function() {
    return bower();
});

// update bower dependencies
gulp.task('update', ['javascript', 'bootstrap-sass', 'font-awesome']);

// Default task
gulp.task('default', ['update', 'sass']);