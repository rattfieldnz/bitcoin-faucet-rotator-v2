var gulp = require('gulp');
var sass = require('gulp-ruby-sass');
var rename = require('gulp-rename');
var elixir = require('laravel-elixir');
var concatCSS = require('gulp-concat-css');
var minifyCSS = require('gulp-minify-css');
var sourcemaps = require('gulp-sourcemaps');
var uglify = require('gulp-uglify');

/**
 * Copy any needed files.
 *
 * Do a 'gulp copyfiles' after bower updates
 */
gulp.task("copyfiles", function() {

    // Copy jQuery
    gulp.src("vendor/bower_components/jquery/dist/jquery.js")
        .pipe(gulp.dest("resources/assets/js/jquery"));

    // JQuery UI //
    gulp.src("vendor/bower_components/jquery-ui/ui/**")
        .pipe(gulp.dest("resources/assets/js/jquery-ui/"));

    gulp.src("vendor/bower_components/jquery-ui/jquery-ui.js")
        .pipe(gulp.dest("resources/assets/js/jquery-ui"));

    // Bootstrap //
    gulp.src("vendor/bower_components/bootstrap/dist/css/bootstrap.css")
        .pipe(gulp.dest("resources/assets/css/bootstrap"));

    gulp.src("vendor/bower_components/bootstrap/dist/js/bootstrap.js")
        .pipe(gulp.dest("resources/assets/js/bootstrap"));

    gulp.src("vendor/bower_components/bootstrap/dist/fonts/**")
        .pipe(gulp.dest("resources/assets/fonts/bootstrap"));

    // Font Awesome //
    gulp.src("vendor/bower_components/font-awesome/css/font-awesome.css")
        .pipe(gulp.dest("resources/assets/css/font-awesome/"));

    gulp.src("vendor/bower_components/font-awesome/fonts/**")
        .pipe(gulp.dest("resources/assets/fonts/font-awesome"));

    // AdminLTE
    gulp.src("vendor/bower_components/admin-lte/dist/css/**/*.css")
        .pipe(gulp.dest("resources/assets/css/admin-lte"));

    gulp.src("vendor/bower_components/admin-lte/dist/img/**/*")
        .pipe(gulp.dest("resources/assets/img/admin-lte"));

    gulp.src("vendor/bower_components/admin-lte/dist/js/app.js")
        .pipe(gulp.dest("resources/assets/js/admin-lte"));

    // DataTables //
    gulp.src("vendor/bower_components/datatables/media/css/*.css")
        .pipe(gulp.dest("resources/assets/css/datatables"));

    gulp.src("vendor/bower_components/datatables/media/images/**")
        .pipe(gulp.dest("resources/assets/img/datatables"));

    gulp.src("vendor/bower_components/datatables/media/js/*.js")
        .pipe(gulp.dest("resources/assets/js/datatables"));

    // iCheck //
    gulp.src("vendor/bower_components/iCheck/skins/**/*")
        .pipe(gulp.dest("resources/assets/css/iCheck/skins"));

    gulp.src("vendor/bower_components/iCheck/icheck.js")
        .pipe(gulp.dest("resources/assets/js/iCheck"));

    // ionicons //
    gulp.src("vendor/bower_components/ionicons/css/*.css")
        .pipe(gulp.dest("resources/assets/css/ionicons"));

    gulp.src("vendor/bower_components/ionicons/fonts/**")
        .pipe(gulp.dest("resources/assets/fonts/ionicons"));

    gulp.src("vendor/bower_components/ionicons/png/**/*")
        .pipe(gulp.dest("resources/assets/img/ionicons"));

    // select2 //
    gulp.src("vendor/bower_components/select2/dist/css/*.css")
        .pipe(gulp.dest("resources/assets/css/select2"));

    gulp.src("vendor/bower_components/select2/dist/js/**/*.js")
        .pipe(gulp.dest("resources/assets/js/select2"));

    // BlockAdBlock //
    gulp.src("vendor/bower_components/blockadblock/blockadblock.js")
        .pipe(gulp.dest("resources/assets/js/blockadblock"));

});

/**
 * Default gulp is to run this elixir stuff
 */
elixir(function(mix) {

    // Combine needed Javascript/JQuery files
    mix.scripts([
            'js/jquery/jquery.js',
            'js/jquery-ui/jquery-ui.js',
            'js/bootstrap/bootstrap.js',
            'js/admin-lte/app.js',
            'js/datatables/jquery.dataTables.js',
            'js/iCheck/icheck.js',
            'js/select2/select2.full.js',
            'js/custom/jquery.doubleScroll.js',
            'js/custom/jquery.livepreview.js',
            'js/custom/jquery.tablesorter.js',
            'js/custom/tablesorter_custom_code.js',
            'js/blockadblock/blockadblock.js'
        ],
        'public/assets/js/mainScripts.js',
        'resources/assets/'
    );

    // Combine CSS
    //gulp.src('resources/assets/css/**/*.css')
    //    .pipe(sourcemaps.init())
    //    .pipe(concatCSS("mainStyles.css"))
    //    .pipe(sourcemaps.write())
    //    .pipe(gulp.dest('public/assets/css/'));

});

gulp.task('minifycss', function(){

    // Minify CSS
    return gulp.src('public/assets/css/mainStyles.css')
        .pipe(minifyCSS())
        .pipe(rename('mainStyles.min.css'))
        .pipe(gulp.dest('public/assets/css/'));

});

gulp.task('minifyjs', function(){
    compressMainJS();
    //compressMainRotator();
    //compressPaymentProcessorRotator();
});

function compressMainJS(){
    return gulp.src('public/assets/js/mainScripts.js')
        .pipe(uglify())
        .pipe(rename("mainScripts.min.js"))
        .pipe(gulp.dest('public/assets/js/'));
}

function compressMainRotator(){
    return gulp.src('public/assets/js/rotator.js')
        .pipe(uglify())
        .pipe(rename('rotator.min.js'))
        .pipe(gulp.dest('public/assets/js/'));
}

function compressPaymentProcessorRotator(){
    return gulp.src('public/assets/js/paymentProcessorRotator.js')
        .pipe(uglify())
        .pipe(rename('paymentProcessorRotator.min.js'))
        .pipe(gulp.dest('public/assets/js/'));
}