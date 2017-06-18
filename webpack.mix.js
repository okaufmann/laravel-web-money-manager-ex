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
    .sass('resources/assets/css/app.scss', 'public/css')
    .sass('resources/assets/css/vendor.scss', 'public/css')
    .less('resources/assets/css/kendo.less', 'public/css');

if (mix.config.inProduction) {
    mix.version();
    mix.version('js/message.js');
} else {
    mix.sourceMaps();
}