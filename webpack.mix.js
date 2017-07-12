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

// setup typescript
mix.webpackConfig({
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                loader: 'ts-loader',
                options: {appendTsSuffixTo: [/\.vue$/]},
                exclude: /node_modules/,
            },
        ],
    },
    resolve: {
        extensions: ['*', '.js', '.jsx', '.vue', '.ts', '.tsx'],
    },
});

mix.js('resources/assets/js/app.ts', 'public/js')
    .js('resources/assets/js/vendor.js', 'public/js')
    .js('resources/assets/js/transaction-form.ts', 'public/js')
    .js('resources/assets/js/i18n/messages-de_CH.js', 'public/js/i18n')
    .js('resources/assets/js/i18n/messages-de_DE.js', 'public/js/i18n')
    .js('resources/assets/js/i18n/messages-en_US.js', 'public/js/i18n')
    .js('resources/assets/js/i18n/messages-en_GB.js', 'public/js/i18n')
    .sass('resources/assets/css/app.scss', 'public/css')
    .sass('resources/assets/css/vendor.scss', 'public/css')
    .less('resources/assets/css/kendo.less', 'public/css');

if (mix.inProduction) {
    mix.version();
    mix.version('public/js/messages.js');
} else {
    // mix.sourceMaps();
}