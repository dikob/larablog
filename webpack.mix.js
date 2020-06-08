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

/* replaced by below to resolve the chrome warning ... DevTools failed to load SourceMap: Could not load content for ...popper.js.map  HTTP error: status code 404, net::ERR_HTTP_RESPONSE_CODE_FAILURE
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');
*/

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css').js('node_modules/popper.js/dist/popper.js', 'public/js').sourceMaps();
