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

mix.js('resources/assets/js/app.js',                                                'public/js')  //Vue.js; Source-> Destination
   .js('resources/assets/js/login/login.js',                                        'public/js/login')  
   .js('resources/assets/js/ShopPaypalSimple/LazyLoad/jquery.lazyload.js',          'public/js/ShopPaypalSimple/LazyLoad/jquery.lazyload.js') 
   .js('resources/assets/js/ShopPaypalSimple/autocomplete.js',                      'public/js/ShopPaypalSimple/autocomplete.js') 
   .js('resources/assets/js/ShopPaypalSimple/cart.js',                              'public/js/ShopPaypalSimple/cart.js') 
   .js('resources/assets/js/ShopPaypalSimple/my_LazyLoad_Shop_Simple.js',           'public/js/ShopPaypalSimple/my_LazyLoad_Shop_Simple.js') 
   .js('resources/assets/js/ShopPaypalSimple/shopSimple.js',                        'public/js/ShopPaypalSimple/shopSimple.js')
   .js('resources/assets/js/ShopPaypalSimple/shopSimple_Loader.js',                 'public/js/ShopPaypalSimple/shopSimple_Loader.js')    
   .js('resources/assets/js/ShopPaypalSimple_Admin/Products/product_tabs.js',       'public/js/ShopPaypalSimple_Admin/Products/product_tabs.js')    
   .js('resources/assets/js/ShopPaypalSimple_Admin/ViewOrders/adminOrders.js',      'public/js/ShopPaypalSimple_Admin/ViewOrders/adminOrders.js')    
   .js('resources/assets/js/ShopPaypalSimple_Admin/ajax_count_orders_quantity.js',  'public/js/ShopPaypalSimple_Admin/ajax_count_orders_quantity.js')    

 
   .sass('resources/assets/sass/app.scss', 'public/css') //SAAS
   .styles([                                      //for pure CSS
        'resources/assets/css/my_css.css',
        //'public/css/vendor/videojs.css'
          ], 'public/css/my_css.css') ;   //final output to folder 
