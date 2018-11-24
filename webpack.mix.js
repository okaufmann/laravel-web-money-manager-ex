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

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/vendor.js', 'public/js')
    .js('resources/js/transaction-form.js', 'public/js')
    .js('resources/js/i18n/messages-de_CH.js', 'public/js/i18n')
    .js('resources/js/i18n/messages-de_DE.js', 'public/js/i18n')
    .js('resources/js/i18n/messages-en_US.js', 'public/js/i18n')
    .js('resources/js/i18n/messages-en_GB.js', 'public/js/i18n')
    .sass('resources/css/app.scss', 'public/css')
    .sass('resources/css/vendor.scss', 'public/css')
    .less('resources/css/kendo.less', 'public/css');

if (mix.inProduction()) {
    mix.version();
    mix.version('public/js/messages.js');
}