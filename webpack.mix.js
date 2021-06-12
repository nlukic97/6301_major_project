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
// mix.webpackConfig({
//     resolve: {
//         modules: [
//             path.resolve(__dirname, 'vendor/laravel/spark/resources/assets/js')
//         ]
//     }
// });

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/codeMirror/codeExecution.js','public/js/codeMirror/codeExecution.js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css'); //custom code for executing codeMirror
