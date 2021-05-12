<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



//Wpress Rest Api
Route::get('/articles', 'Rest@index');       //http://localhost/laravel+Yii2_widgets/blog_Laravel/public/articles
Route::get('articles/{id}', 'Rest@show');  //http://localhost/laravel+Yii2_widgets/blog_Laravel/public/articles/8
Route::post('articles', 'Rest@store');
Route::put('articles/{id}', 'Rest@update');
Route::delete('articles/{id}', 'Rest@delete');


//AppointmentRoom List Rest Api
Route::get('/rooms',       'AppointmentRest@index');      // http://localhost/laravel+Yii2_widgets/blog_Laravel/public/rooms
Route::get('/getCalendar', 'AppointmentRest@getCalendar');      // http://localhost/laravel+Yii2_widgets/blog_Laravel/public/rooms
