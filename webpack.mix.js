const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
const files = ["app", "pages/home", "pages/project"];

mix.js('resources/js/app.js', 'public/js')

files.forEach(file => {
    mix.sass(`resources/sass/${file}.scss`, "public/css", [])
})


mix.browserSync({proxy: '127.0.0.1:8000', notify: false});

