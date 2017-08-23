let gulp = require('gulp');
let sass = require('gulp-ruby-sass');
let rename = require('gulp-rename');
let elixir = require('laravel-elixir');
let concatCSS = require('gulp-concat-css');
let minifyCSS = require('gulp-minify-css');
let sourcemaps = require('gulp-sourcemaps');
let uglify = require('gulp-uglify');
let htmlmin = require('gulp-htmlmin');

/**
 * Copy any needed files.
 *
 * Do a 'gulp copyfiles' after bower updates
 */
gulp.task("copyfiles", function() {

    // Copy jQuery
    gulp.src("vendor/bower_components/jquery/dist/jquery.js")
        .pipe(gulp.dest("resources/assets/js/jquery/"));

    // JQuery UI //
    /**gulp.src("vendor/bower_components/jquery-ui/ui/**")
        .pipe(gulp.dest("resources/assets/js/jquery-ui/"));

    gulp.src("vendor/bower_components/jquery-ui/jquery-ui.js")
        .pipe(gulp.dest("resources/assets/js/jquery-ui/"));

    gulp.src("vendor/bower_components/jquery-ui/themes/base/jquery-ui.css")
        .pipe(gulp.dest("resources/assets/css/jquery-ui/"));

    gulp.src("vendor/bower_components/jquery-ui/themes/base/images/*")
        .pipe(gulp.dest("public/assets/images/jquery-ui/"));**/

    // Bootstrap //
    gulp.src("vendor/bower_components/bootstrap/dist/css/bootstrap.css")
        .pipe(gulp.dest("resources/assets/css/bootstrap/"));

    gulp.src("vendor/bower_components/bootstrap/dist/js/bootstrap.js")
        .pipe(gulp.dest("resources/assets/js/bootstrap/"));

    gulp.src("vendor/bower_components/bootstrap/dist/fonts/**")
        .pipe(gulp.dest("public/assets/fonts/"));

    // Local Forage //
    gulp.src("vendor/bower_components/localforage/dist/localforage.js")
        .pipe(gulp.dest("resources/assets/js/localforage/"));

    // Font Awesome //
    gulp.src("vendor/bower_components/font-awesome/css/font-awesome.css")
        .pipe(gulp.dest("resources/assets/css/font-awesome"));

    gulp.src("vendor/bower_components/font-awesome/fonts/**")
        .pipe(gulp.dest("public/assets/fonts/"));

    // AdminLTE
    gulp.src("vendor/bower_components/admin-lte/dist/css/**/*.css")
        .pipe(gulp.dest("resources/assets/css/admin-lte/"));

    gulp.src("vendor/bower_components/admin-lte/bootstrap/css/*.css.map")
        .pipe(gulp.dest("public/assets/css/"));

    gulp.src("vendor/bower_components/admin-lte/dist/img/**/*")
        .pipe(gulp.dest("resources/assets/img/admin-lte/"));

    gulp.src("vendor/bower_components/admin-lte/dist/js/app.js")
        .pipe(gulp.dest("resources/assets/js/admin-lte/"));

    // DataTables //
    gulp.src("vendor/bower_components/datatables/media/css/*.css")
        .pipe(gulp.dest("resources/assets/css/datatables/"));

    gulp.src("vendor/bower_components/datatables/media/images/**")
        .pipe(gulp.dest("resources/assets/img/datatables/"));

    gulp.src("vendor/bower_components/datatables/media/js/*.js")
        .pipe(gulp.dest("resources/assets/js/datatables/"));

    // iCheck //
    gulp.src("vendor/bower_components/iCheck/skins/**/*.css")
        .pipe(gulp.dest("resources/assets/css/iCheck/skins/"));

    gulp.src("vendor/bower_components/iCheck/skins/**/*.png")
        .pipe(gulp.dest("public/assets/images/iCheck/"));

    gulp.src("vendor/bower_components/iCheck/skins/*.png")
        .pipe(gulp.dest("public/assets/images/iCheck/"));

    gulp.src("vendor/bower_components/iCheck/icheck.js")
        .pipe(gulp.dest("resources/assets/js/iCheck/"));

    // ionicons //
    gulp.src("vendor/bower_components/ionicons/css/*.css")
        .pipe(gulp.dest("resources/assets/css/ionicons/"));

    gulp.src("vendor/bower_components/ionicons/fonts/**")
        .pipe(gulp.dest("resources/assets/fonts/"));

    gulp.src("vendor/bower_components/ionicons/png/**/*")
        .pipe(gulp.dest("resources/assets/img/ionicons/"));

    // select2 //
    gulp.src("vendor/bower_components/select2/dist/css/*.css")
        .pipe(gulp.dest("resources/assets/css/select2/"));

    gulp.src("vendor/bower_components/select2/dist/js/**/*.js")
        .pipe(gulp.dest("resources/assets/js/select2/"));

    // Table sorter theme //
    gulp.src("resources/assets/css/table_sorter_themes/images/**")
        .pipe(gulp.dest("public/assets/images/table-sorter/"));

    // Cookie Consent //
    gulp.src("vendor/bower_components/cookieconsent/src/styles/**/*.css")
        .pipe(gulp.dest("resources/assets/css/cookieconsent/"));

    gulp.src("vendor/bower_components/cookieconsent/src/cookieconsent.js")
        .pipe(gulp.dest("resources/assets/js/cookieconsent/"));

    // DataTables Core
    gulp.src("vendor/bower_components/datatables.net/js/jquery.dataTables.js")
        .pipe(gulp.dest("resources/assets/js/datatables.net/"));

    // DataTables Bootstrap
    gulp.src("vendor/bower_components/datatables.net-bs/css/dataTables.bootstrap.css")
        .pipe(gulp.dest("resources/assets/css/datatables.net/"));

    gulp.src("vendor/bower_components/datatables.net-bs/js/dataTables.bootstrap.js")
        .pipe(gulp.dest("resources/assets/js/datatables.net/"));

    // ChartJS
    gulp.src("vendor/bower_components/chart.js/dist/Chart.js")
        .pipe(gulp.dest("resources/assets/js/chart.js/"));

    // JQuery Progress Timer (for progress bars)
    gulp.src("vendor/bower_components/jquery-progresstimer/dist/js/jquery.progresstimer.js")
        .pipe(gulp.dest("resources/assets/js/jquery-progresstimer/"));

    // JS Cookie
    gulp.src("vendor/bower_components/js-cookie/src/js.cookie.js")
        .pipe(gulp.dest("resources/assets/js/js-cookie/"));

});

/**
 * Default gulp is to run this elixir stuff
 */
elixir(function(mix) {

    // Combine needed Javascript/JQuery files
    mix.scripts([
            'js/jquery/jquery.js',
            //'js/jquery-ui/jquery-ui.js',
            'js/jquery-ui-custom/jquery-ui.js',
            'js/bootstrap/bootstrap.js',
            'js/localforage/localforage.js',
            'js/admin-lte/app.js',
            'js/datatables/jquery.dataTables.js',
            'js/iCheck/icheck.js',
            'js/select2/select2.full.js',
            'js/custom/jquery.doubleScroll.js',
            'js/custom/jquery.livepreview.js',
            'js/custom/jquery.tablesorter.js',
            'js/datatables.net/jquery.dataTables.js',
            'js/datatables.net/dataTables.bootstrap.js',
            'js/custom/tablesorter_custom_code.js',
            'js/cookieconsent/cookieconsent.js',
            'js/jquery-progresstimer/jquery.progresstimer.js',
            'js/laroute/laroute.js',
            'js/js-cookie/js.cookie.js',
            'js/custom/custom.js'
        ],
        'public/assets/js/mainScripts.js',
        'resources/assets/'
    );

    mix.scripts([
            'js/datatables.net/jquery.dataTables.js',
            'js/datatables.net/dataTables.bootstrap.js'
        ],
        'public/assets/js/datatables.net/datatables.js',
        'resources/assets/'
    );

    gulp.src("resources/assets/js/chart.js/Chart.js")
        .pipe(gulp.dest("public/assets/js/chart.js/"));

    gulp.src("resources/assets/js/custom/stats.js")
        .pipe(gulp.dest("public/assets/js/stats/"));

    gulp.src([
        'resources/assets/css/datatables.net/dataTables.bootstrap.css'
    ])
    .pipe(sourcemaps.init())
    .pipe(concatCSS("datatables.css"))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest('public/assets/css/datatables.net/'));

    // Combine CSS //
    gulp.src([
            'resources/assets/css/bootstrap/bootstrap.css',
            'resources/assets/css/font-awesome/font-awesome.css',
            //'resources/assets/css/jquery-ui/jquery-ui.css',
            'resources/assets/css/jquery-ui-custom/*.css',
            'resources/assets/select2/select2.css',
            'resources/assets/css/iCheck/skins/_all.css',
            'resources/assets/css/datatables/dataTables.bootstrap.css',
            'resources/assets/css/ionicons/ionicons.css',
            'resources/assets/css/admin-lte/_all-skins.css',
            'resources/assets/css/admin-lte/AdminLTE.css',
            'resources/assets/css/admin-lte/AdminLTE-*.css',
            'resources/assets/css/table_sorter_themes/style.css',
            'resources/assets/css/cookieconsent/**/*.css',
            'resources/assets/css/custom.css'
        ]
    )
    .pipe(sourcemaps.init())
    .pipe(concatCSS("mainStyles.css"))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest('public/assets/css/'));

});

gulp.task('compress', function() {
    let opts = {
        collapseWhitespace: true,
        removeAttributeQuotes: true,
        removeComments: true,
        minifyJS: true
    };

    return gulp.src('./storage/framework/views/**/*')
        .pipe(htmlmin(opts))
        .pipe(gulp.dest('./storage/framework/views/'));
});

gulp.task('minifycss', function(){

    // Minify CSS
    compressDataTablesCss();
    return gulp.src('public/assets/css/mainStyles.css')
        .pipe(minifyCSS())
        .pipe(rename('mainStyles.min.css'))
        .pipe(gulp.dest('public/assets/css/'));

});

gulp.task('minifyjs', function(){
    compressDataTablesJs();
    compressChartJsScripts();
    compressStatsScripts();
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

function compressDataTablesJs(){
    return gulp.src('public/assets/js/datatables.net/datatables.js')
        .pipe(uglify())
        .pipe(rename("datatables.min.js"))
        .pipe(gulp.dest('public/assets/js/datatables.net/'));
}

function compressDataTablesCss(){
    return gulp.src('public/assets/css/datatables.net/datatables.css')
        .pipe(minifyCSS())
        .pipe(rename('datatables.min.css'))
        .pipe(gulp.dest('public/assets/css/datatables.net/'));
}

function compressChartJsScripts(){
    return gulp.src('public/assets/js/chart.js/Chart.js')
        .pipe(uglify())
        .pipe(rename("Chart.min.js"))
        .pipe(gulp.dest('public/assets/js/chart.js/'));
}

function compressStatsScripts(){
    return gulp.src('public/assets/js/stats/stats.js')
        .pipe(uglify())
        .pipe(rename("stats.min.js"))
        .pipe(gulp.dest('public/assets/js/stats/'));
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