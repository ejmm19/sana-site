const gulp = require('gulp');
const less = require('gulp-less');
const cleanCSS = require('gulp-clean-css');
const path = require('path');

gulp.task('less-compile-theme', function(done) {
    console.log('Compilando y minificando LESS para el tema...');
    return gulp.src('wp-content/themes/sanamante/assets/css/style.less')
        .pipe(less())
        .pipe(cleanCSS({ compatibility: 'ie8' }))
        .pipe(gulp.dest('wp-content/themes/sanamante'))
        .on('end', () => {
            console.log('Compilación y minificación del tema completada.');
            done();
        });
});

gulp.task('less-compile-plugins', function(done) {
    console.log('Compilando y minificando LESS para el plugins...');
    return gulp.src('wp-content/plugins/booking-basic-sm/templates/wp-admin/css/admin.less')
        .pipe(less())
        .pipe(cleanCSS({ compatibility: 'ie8' }))
        .pipe(gulp.dest('wp-content/plugins/booking-basic-sm/templates/wp-admin/css'))
        .on('end', () => {
            console.log('Compilación y minificación del plugins completada.');
            done();
        });
});

gulp.task('watch', function() {
    console.log('Observando cambios en LESS del tema...');
    gulp.watch('wp-content/themes/sanamante/assets/css/**/*.less', gulp.series('less-compile-theme'));

    console.log('Observando cambios en LESS del plugins...');
    gulp.watch('wp-content/plugins/booking-basic-sm/templates/wp-admin/css/**/*.less', gulp.series('less-compile-plugins'));
});

gulp.task('default', gulp.series('watch'));
