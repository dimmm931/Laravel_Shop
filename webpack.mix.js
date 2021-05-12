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

mix.js('resources/assets/js/app.js',         'public/js')  //Vue.js; Source-> Destination
   .js('resources/assets/js/login/login.js', 'public/js/login')  
   .js('resources/assets/js/rbac/my-rbac.js','public/js/rbac') 
 
   .sass('resources/assets/sass/app.scss', 'public/css') //SAAS
   .styles([                                      //for pure CSS
        'resources/assets/css/my_css.css',
        //'public/css/vendor/videojs.css'
          ], 'public/css/my_css.css') ;   //final output to folder 
