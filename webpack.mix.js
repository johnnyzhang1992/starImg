let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.react('resources/assets/js/app.js', 'public/js')
    .react('resources/assets/js/react-img.js', 'public/js')
    .react('resources/assets/js/star.js', 'public/js')
    .react('resources/assets/js/star_list.js', 'public/js')
    .react('resources/assets/js/pin-img.js', 'public/js')
    .react('resources/assets/js/new_star_list', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .sass('resources/assets/sass/react-img.scss', 'public/css')
    // .sourceMaps()
    .version();
