<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//default page
/* Route::get('/', function () {
    return view('welcome');
}); */
Route::get('/',     'HomeController@index');
Route::get('/home', 'ShopSimplePayPal\ShopPayPalSimpleController@index')->name('shopSimple');

Auth::routes();

//ShopSimple
Route::get('/shopSimple',          'ShopSimplePayPal\ShopPayPalSimpleController@index')->name('shopSimple'); //display shopSimple start page
Route::get('/cart',                'ShopSimplePayPal\ShopPayPalSimpleController@cart')->name('cart'); //display shopSimple start page
Route::get('/showOneProduct/{id}', 'ShopSimplePayPal\ShopPayPalSimpleController@showOneProductt')->name('showOneProduct');
Route::post('/addToCart',          'ShopSimplePayPal\ShopPayPalSimpleController@storeToCart')->name('/addToCart');  //route to method to add to cart, send via POST form
Route::post('/checkOut',           'ShopSimplePayPal\ShopPayPalSimpleController@checkOut')->name('/checkOut');  //route to method to checkOut, gets form data with Final Cart send via POST form and redirects to GET /checkOut2
//just to prevent users entering get url for post method, i.e if user enter /checkOut manually in browser
Route::get('/checkOut', function () { throw new \App\Exceptions\myException('Bad request. Not POST request, You are not expected to enter this page.'); });

Route::get('/checkOut2',   'ShopSimplePayPal\ShopPayPalSimpleController@checkOut2')->name('/checkOut2');  //route to method to checkOut (page with shipping details), send via GET
Route::post('/payPage1',   'ShopSimplePayPal\ShopPayPalSimpleController@pay1')->name('/payPage1');        //route to get <form> data via $_POST from Checkout/Order page (Shipping details (address, phone. etc)) and redirects to $_GET page route {payPage2}. 
Route::get('/payPage2',    'ShopSimplePayPal\ShopPayPalSimpleController@pay2')->name('/payPage2');        //route final pay page, send via POST form
Route::get('/pay-or-fail', 'ShopSimplePayPal\ShopPayPalSimpleController@payOrFail')->name('pay-or-fail'); //final payment page, returned by PayPal INP Listener, displays if payment was successfull or not



//Tried but failed Entrust middleware
//Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function() {
	
//ShopSimple Admin Panel
Route::get('/shopAdminPanel', 'ShopPayPalSimple_AdminPanel\ShopPayPalSimple_AdminPanel@index')->name('shopAdminPanel'); //display Admin Panel start page
Route::get('/admin-orders',   'ShopPayPalSimple_AdminPanel\ShopPayPalSimple_AdminPanel@orders')->name('admin-orders'); //display Admin Panel ....
Route::get('/count-orders',   'ShopPayPalSimple_AdminPanel\ShopPayPalSimple_AdminPanel@countOrders'); // fot ajax counting Orders in Admin panel
Route::post('/updateStatus',  'ShopPayPalSimple_AdminPanel\ShopPayPalSimple_AdminPanel@updateStatusField')->name('/updateStatus');   //route to get <form> data via $_POST from {ShopPayPalSimple_AdminPanel@orders} page ()) and redirects back. 
Route::get('/admin-products', 'ShopPayPalSimple_AdminPanel\ShopPayPalSimple_AdminPanel@products')->name('admin-products'); //display Admin Panel with all products (and option to edit them)
Route::get('/admin-add-product', 'ShopPayPalSimple_AdminPanel\ShopPayPalSimple_AdminPanel@addProduct')->name('admin-add-product'); //display Admin Page to add a product
Route::post('/storeNewproduct',  'ShopPayPalSimple_AdminPanel\ShopPayPalSimple_AdminPanel@storeProduct')->name('storeNewproduct'); //display Admin Page to add a product

Route::get('/admin-one-product/{id}', 'ShopPayPalSimple_AdminPanel\ShopPayPalSimple_AdminPanel@showOneProduct')->name('admin-one-product'); //show one product by ID
Route::get('admin-edit-product/{id}', 'ShopPayPalSimple_AdminPanel\ShopPayPalSimple_AdminPanel@editProduct')->name('admin-edit-product/{id}'); //display Admin Page to edit an existing product
Route::post('/admin-delete-product',  'ShopPayPalSimple_AdminPanel\ShopPayPalSimple_AdminPanel@deleteProduct')->name('/admin-delete-product');  //route to method to delete certian product by ID. Sent by POST form

Route::post('/addQuantity',    'ShopPayPalSimple_AdminPanel\ShopPayPalSimple_AdminPanel@addStockQuantity')  ->name('addQuantity');   //route to get <form> data via $_POST from page {'/admin-edit-product/{id}'}) (function editProduct()),      add++ quantity to   table {shop_quantity} and redirects it to back  route {'/admin-edit-product/{id}'}. 
Route::post('/minusQuantity',  'ShopPayPalSimple_AdminPanel\ShopPayPalSimple_AdminPanel@minusStockQuantity')->name('minusQuantity'); //route to get <form> data via $_POST from page {'/admin-edit-product/{id}'}) (function editProduct()), minus-- quantity from table {shop_quantity} and redirects it to back  route {'/admin-edit-product/{id}'}. 

 

Route::get('/404', function () {
    return abort(404);
});


