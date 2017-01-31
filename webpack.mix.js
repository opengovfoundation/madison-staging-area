const { mix } = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
   // .extract(['lodash', 'jquery', 'bootstrap-sass'])
   .js('resources/assets/js/annotator-madison.js', 'public/js')
   .js('resources/assets/js/document.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .copy('resources/assets/vendor/js/annotator-full.min.js', 'public/js/')
   .copy('resources/assets/img', 'public/img')
   .copy('resources/assets/vendor/css/annotator.min.css', 'public/css/')
   .copy('node_modules/font-awesome/fonts/', 'public/fonts/')
   .version()
   ;
