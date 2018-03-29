let gulp = require('gulp');
let sass = require('gulp-ruby-sass');
let rename = require('gulp-rename');
let elixir = require('laravel-elixir');
let concatCSS = require('gulp-concat-css');
let minifyCSS = require('gulp-minify-css');
let sourcemaps = require('gulp-sourcemaps');
let uglify = require('gulp-uglify');
let htmlmin = require('gulp-htmlmin');
let gulputil = require('gulp-util');

/**
 * Copy any needed files.
 *
 * Do a 'gulp copyfiles' after bower updates
 */
gulp.task("copyfiles", function() {

    // Copy jQuery
    gulp.src("node_modules/@bower_components/jquery/dist/jquery.js")
        .pipe(gulp.dest("resources/assets/js/jquery/"));

    // JQuery UI //
    /**gulp.src("node_modules/@bower_components/jquery-ui/ui/**")
        .pipe(gulp.dest("resources/assets/js/jquery-ui/"));

    gulp.src("node_modules/@bower_components/jquery-ui/jquery-ui.js")
        .pipe(gulp.dest("resources/assets/js/jquery-ui/"));

    gulp.src("node_modules/@bower_components/jquery-ui/themes/base/jquery-ui.css")
        .pipe(gulp.dest("resources/assets/css/jquery-ui/"));

    gulp.src("node_modules/@bower_components/jquery-ui/themes/base/images/*")
        .pipe(gulp.dest("public/assets/images/jquery-ui/"));**/

    // JQuery TimePicker AddOn
    gulp.src("node_modules/@bower_components/jqueryui-timepicker-addon/dist/jquery-ui-timepicker-addon.css")
        .pipe(gulp.dest("resources/assets/css/jqueryui-timepicker-addon/"));

    gulp.src("node_modules/@bower_components/jqueryui-timepicker-addon/dist/jquery-ui-timepicker-addon.js")
        .pipe(gulp.dest("resources/assets/js/jqueryui-timepicker-addon/"));

    gulp.src("node_modules/@bower_components/jqueryui-timepicker-addon/dist/jquery-ui-sliderAccess.js")
        .pipe(gulp.dest("resources/assets/js/jqueryui-timepicker-addon/"));

    // Bootstrap //
    gulp.src("node_modules/@bower_components/bootstrap/dist/css/bootstrap.css")
        .pipe(gulp.dest("resources/assets/css/bootstrap/"));

    gulp.src("node_modules/@bower_components/bootstrap/dist/js/bootstrap.js")
        .pipe(gulp.dest("resources/assets/js/bootstrap/"));

    gulp.src("node_modules/@bower_components/bootstrap/dist/fonts/**")
        .pipe(gulp.dest("public/assets/fonts/"));

    // Bootstrap Switch
    gulp.src("node_modules/@bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css")
        .pipe(gulp.dest("resources/assets/css/bootstrap-switch/"));

    gulp.src("node_modules/@bower_components/bootstrap-switch/dist/js/bootstrap-switch.js")
        .pipe(gulp.dest("resources/assets/js/bootstrap-switch/"));

    // Bootstrap Select
    gulp.src("node_modules/@bower_components/bootstrap-select/dist/css/bootstrap-select.css")
        .pipe(gulp.dest("resources/assets/css/bootstrap-select/"));

    gulp.src("node_modules/@bower_components/bootstrap-select/dist/js/bootstrap-select.js")
        .pipe(gulp.dest("resources/assets/js/bootstrap-select/"));

    // Local Forage //
    gulp.src("node_modules/@bower_components/localforage/dist/localforage.js")
        .pipe(gulp.dest("resources/assets/js/localforage/"));

    // Font Awesome //
    gulp.src("node_modules/@bower_components/font-awesome/css/font-awesome.css")
        .pipe(gulp.dest("resources/assets/css/font-awesome"));

    gulp.src("node_modules/@bower_components/font-awesome/fonts/**")
        .pipe(gulp.dest("public/assets/fonts/"));

    // AdminLTE
    gulp.src("node_modules/@bower_components/admin-lte/dist/css/**/*.css")
        .pipe(gulp.dest("resources/assets/css/admin-lte/"));

    gulp.src("node_modules/@bower_components/admin-lte/bootstrap/css/*.css.map")
        .pipe(gulp.dest("public/assets/css/"));

    gulp.src("node_modules/@bower_components/admin-lte/dist/img/**/*")
        .pipe(gulp.dest("resources/assets/img/admin-lte/"));

    gulp.src("node_modules/@bower_components/admin-lte/dist/js/app.js")
        .pipe(gulp.dest("resources/assets/js/admin-lte/"));

    // DataTables //
    gulp.src("node_modules/@bower_components/datatables.net-bs/css/dataTables.bootstrap.css")
        .pipe(gulp.dest("resources/assets/css/datatables.net/"));

    gulp.src("node_modules/@bower_components/datatables.net-bs/js/dataTables.bootstrap.js")
        .pipe(gulp.dest("resources/assets/js/datatables.net/"));

    gulp.src("node_modules/@bower_components/datatables.net/js/jquery.dataTables.js")
        .pipe(gulp.dest("resources/assets/js/datatables.net/"));

    // iCheck //
    gulp.src("node_modules/@bower_components/iCheck/skins/**/*.css")
        .pipe(gulp.dest("resources/assets/css/iCheck/skins/"));

    gulp.src("node_modules/@bower_components/iCheck/skins/**/*.png")
        .pipe(gulp.dest("public/assets/images/iCheck/"));

    gulp.src("node_modules/@bower_components/iCheck/skins/*.png")
        .pipe(gulp.dest("public/assets/images/iCheck/"));

    gulp.src("node_modules/@bower_components/iCheck/icheck.js")
        .pipe(gulp.dest("resources/assets/js/iCheck/"));

    // ionicons //
    gulp.src("node_modules/@bower_components/ionicons/css/*.css")
        .pipe(gulp.dest("resources/assets/css/ionicons/"));

    gulp.src("node_modules/@bower_components/ionicons/fonts/**")
        .pipe(gulp.dest("resources/assets/fonts/"));

    gulp.src("node_modules/@bower_components/ionicons/png/**/*")
        .pipe(gulp.dest("resources/assets/img/ionicons/"));

    // Table sorter theme //
    gulp.src("resources/assets/css/table_sorter_themes/images/**")
        .pipe(gulp.dest("public/assets/images/table-sorter/"));

    // Cookie Consent //
    gulp.src("node_modules/@bower_components/cookieconsent/src/styles/**/*.css")
        .pipe(gulp.dest("resources/assets/css/cookieconsent/"));

    gulp.src("node_modules/@bower_components/cookieconsent/src/cookieconsent.js")
        .pipe(gulp.dest("resources/assets/js/cookieconsent/"));

    // ChartJS
    gulp.src("node_modules/@bower_components/chart.js/dist/Chart.js")
        .pipe(gulp.dest("resources/assets/js/chart.js/"));

    // JQuery Progress Timer (for progress bars)
    gulp.src("node_modules/@bower_components/jquery-progresstimer/dist/js/jquery.progresstimer.js")
        .pipe(gulp.dest("resources/assets/js/jquery-progresstimer/"));

    // JS Cookie
    gulp.src("node_modules/@bower_components/js-cookie/src/js.cookie.js")
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
            'js/iCheck/icheck.js',
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
            'js/jqueryui-timepicker-addon/jquery-ui-timepicker-addon.js',
            'js/jqueryui-timepicker-addon/jquery-ui-sliderAccess.js',
            'js/bootstrap-select/bootstrap-select.js',
            'js/bootstrap-switch/bootstrap-switch.js',
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

    gulp.src("resources/assets/js/select2/select2.full.min.js")
        .pipe(gulp.dest("public/assets/js/select2/"));

    gulp.src("resources/assets/css/select2/select2.min.css")
        .pipe(gulp.dest("public/assets/css/select2/"));

    gulp.src("resources/assets/js/custom/stats.js")
        .pipe(gulp.dest("public/assets/js/stats/"));

    gulp.src("resources/assets/js/custom/mainRotator.js")
        .pipe(gulp.dest("public/assets/js/rotator-scripts/"));

    gulp.src("resources/assets/js/custom/paymentProcessorRotator.js")
        .pipe(gulp.dest("public/assets/js/rotator-scripts/"));

    gulp.src("resources/assets/js/custom/userFaucetRotator.js")
        .pipe(gulp.dest("public/assets/js/rotator-scripts/"));

    gulp.src("resources/assets/js/custom/userPaymentProcessorRotator.js")
        .pipe(gulp.dest("public/assets/js/rotator-scripts/"));

    gulp.src("resources/assets/js/custom/faucetDatatables.js")
        .pipe(gulp.dest("public/assets/js/faucet-scripts/"));

    gulp.src("resources/assets/js/custom/paymentProcessorDatatables.js")
        .pipe(gulp.dest("public/assets/js/payment-processor-scripts/"));

    gulp.src("resources/assets/js/custom/usersDatatables.js")
        .pipe(gulp.dest("public/assets/js/user-scripts/"));

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
            'resources/assets/css/iCheck/skins/_all.css',
            'resources/assets/css/datatables.net/dataTables.bootstrap.css',
            'resources/assets/css/ionicons/ionicons.css',
            'resources/assets/css/admin-lte/skins/_all-skins.css',
            'resources/assets/css/admin-lte/AdminLTE.css',
            'resources/assets/css/admin-lte/alt/AdminLTE-select2.css',
            'resources/assets/css/admin-lte/alt/AdminLTE-fullcalendar.css',
            'resources/assets/css/table_sorter_themes/style.css',
            'resources/assets/css/cookieconsent/**/*.css',
            'resources/assets/css/bootstrap-switch/bootstrap-switch.css',
            'resources/assets/css/jqueryui-timepicker-addon/jquery-ui-timepicker-addon.css',
            'resources/assets/css/bootstrap-select/bootstrap-select.css',
            'resources/assets/css/bootstrap-switch/bootstrap-switch.css',
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
    compressMainRotator();
    compressPaymentProcessorRotator();
    compressUserFaucetRotator();
    compressUserPaymentProcessorRotator();
    compressFaucetDatatablesScript();
    compressPaymentProcessorsDatatableScript();
    compressUsersDatatableScript();
});

function compressMainJS(){
    return gulp.src('public/assets/js/mainScripts.js')
        .pipe(uglify())
        .on('error', function (err) { gulputil.log(gulputil.colors.red('[Error]'), err.toString()); })
        .pipe(rename("mainScripts.min.js"))
        .pipe(gulp.dest('public/assets/js/'));
}

function compressDataTablesJs(){
    return gulp.src('public/assets/js/datatables.net/datatables.js')
        .pipe(uglify())
        .on('error', function (err) { gulputil.log(gulputil.colors.red('[Error]'), err.toString()); })
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
        .on('error', function (err) { gulputil.log(gulputil.colors.red('[Error]'), err.toString()); })
        .pipe(rename("Chart.min.js"))
        .pipe(gulp.dest('public/assets/js/chart.js/'));
}

function compressStatsScripts(){
    return gulp.src('public/assets/js/stats/stats.js')
        .pipe(uglify())
        .on('error', function (err) { gulputil.log(gulputil.colors.red('[Error]'), err.toString()); })
        .pipe(rename("stats.min.js"))
        .pipe(gulp.dest('public/assets/js/stats/'));
}

function compressMainRotator(){
    return gulp.src('public/assets/js/rotator-scripts/mainRotator.js')
        .pipe(uglify())
        .on('error', function (err) { gulputil.log(gulputil.colors.red('[Error]'), err.toString()); })
        .pipe(rename('mainRotator.min.js'))
        .pipe(gulp.dest('public/assets/js/rotator-scripts/'));
}

function compressPaymentProcessorRotator(){
    return gulp.src('public/assets/js/rotator-scripts/paymentProcessorRotator.js')
        .pipe(uglify())
        .pipe(rename('paymentProcessorRotator.min.js'))
        .pipe(gulp.dest('public/assets/js/rotator-scripts/'));
}

function compressUserFaucetRotator(){
    return gulp.src('public/assets/js/rotator-scripts/userFaucetRotator.js')
        .pipe(uglify())
        .on('error', function (err) { gulputil.log(gulputil.colors.red('[Error]'), err.toString()); })
        .pipe(rename('userFaucetRotator.min.js'))
        .pipe(gulp.dest('public/assets/js/rotator-scripts/'));
}

function compressUserPaymentProcessorRotator(){
    return gulp.src('public/assets/js/rotator-scripts/userPaymentProcessorRotator.js')
        .pipe(uglify())
        .on('error', function (err) { gulputil.log(gulputil.colors.red('[Error]'), err.toString()); })
        .pipe(rename('userPaymentProcessorRotator.min.js'))
        .pipe(gulp.dest('public/assets/js/rotator-scripts/'));
}

function compressFaucetDatatablesScript(){
    return gulp.src('public/assets/js/faucet-scripts/faucetDatatables.js')
        .pipe(uglify())
        .on('error', function (err) { gulputil.log(gulputil.colors.red('[Error]'), err.toString()); })
        .pipe(rename('faucetDatatables.min.js'))
        .pipe(gulp.dest('public/assets/js/faucet-scripts/'));
}

function compressPaymentProcessorsDatatableScript(){
    return gulp.src('public/assets/js/payment-processor-scripts/paymentProcessorDatatables.js')
        .pipe(uglify())
        .on('error', function (err) { gulputil.log(gulputil.colors.red('[Error]'), err.toString()); })
        .pipe(rename('paymentProcessorDatatables.min.js'))
        .pipe(gulp.dest('public/assets/js/payment-processor-scripts/'));
}

function compressUsersDatatableScript(){
    return gulp.src('public/assets/js/user-scripts/usersDatatables.js')
        .pipe(uglify())
        .on('error', function (err) { gulputil.log(gulputil.colors.red('[Error]'), err.toString()); })
        .pipe(rename('usersDatatables.min.js'))
        .pipe(gulp.dest('public/assets/js/user-scripts/'));
}
