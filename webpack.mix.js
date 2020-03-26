const mix = require('laravel-mix');

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

mix.js('resources/js/hrms.js', 'public/js')
    .sass('resources/sass/hrms.scss', 'public/css');

mix.js('resources/js/spms.js', 'public/js')
    .sass('resources/sass/spms.scss', 'public/css');
