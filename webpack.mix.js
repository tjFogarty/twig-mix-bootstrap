let mix = require('laravel-mix').mix;

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

mix.js('src/js/main.js', 'public/js/')
   .sass('src/css/main.scss', 'public/css/')
   .extract(['jquery', 'tether', 'bootstrap'])
   .sourceMaps();

// Full API
// mix.js(src, output);
// mix.extract(vendorLibs);
// mix.sass(src, output);
// mix.less(src, output);
// mix.combine(files, destination);
// mix.copy(from, to);
// mix.minify(file);
// mix.sourceMaps(); // Enable sourcemaps
// mix.version(); // Enable versioning.
// mix.disableNotifications();
// mix.setCachePath('some/folder');
// mix.setPublicPath('path/to/public'); <-- Useful for Node apps.
// mix.webpackConfig({}); <-- Override webpack.config.js, without editing the file directly.
