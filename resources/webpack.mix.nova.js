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

mix
    .setPublicPath('public/vendor/laravel-nova-assets')
    .styles('resources/laravel-nova-assets/theme-styles/*.css', 'public/vendor/laravel-nova-assets/theme-styles.css')
    .styles('resources/laravel-nova-assets/tool-styles/*.css', 'public/vendor/laravel-nova-assets/tool-styles.css')
    .styles('resources/laravel-nova-assets/custom-styles/*.css', 'public/vendor/laravel-nova-assets/custom-styles.css')
    .scripts('resources/laravel-nova-assets/tool-scripts/*.js', 'public/vendor/laravel-nova-assets/tool-scripts.js')
    .scripts('resources/laravel-nova-assets/custom-scripts/*.js', 'public/vendor/laravel-nova-assets/custom-scripts.js')
    .version();

