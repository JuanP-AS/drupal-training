import { src, dest, watch} from 'gulp';
import sass from 'gulp-dart-sass';

export function compileSass() {
    return src('web/modules/custom/**/*.scss')
        .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
        .pipe(dest((file) => file.base)); // Output files to the same directory
}

export function watchSass() {
    watch('web/modules/custom/**/*.scss', compileSass);
}
