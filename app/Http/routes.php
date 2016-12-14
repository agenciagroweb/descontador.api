<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Signup
|--------------------------------------------------------------------------
*/
Route::post('signup', 'SignupController@store');

/*
|--------------------------------------------------------------------------
| Authenticate
|--------------------------------------------------------------------------
*/
Route::post('authenticate', 'AuthenticateController@authenticate');

/*
|--------------------------------------------------------------------------
| Users
|--------------------------------------------------------------------------
*/
Route::resource('user', 'UserController', ['except' => [
    'create', 'edit'
]]);

Route::post('user/{id}/picture', 'UserPictureController@update');
Route::delete('user/{id}/picture', 'UserPictureController@destroy');

/*
|--------------------------------------------------------------------------
| Stores
|--------------------------------------------------------------------------
*/
Route::resource('store', 'StoreController', ['except' => [
    'create', 'edit'
]]);

Route::post('store/{id}/picture', 'StorePictureController@update');
Route::delete('store/{id}/picture', 'StorePictureController@destroy');

/*
|--------------------------------------------------------------------------
| Coupons
|--------------------------------------------------------------------------
*/
Route::resource('coupon', 'CouponController', ['except' => [
    'create', 'edit'
]]);

/*
|--------------------------------------------------------------------------
| Category
|--------------------------------------------------------------------------
*/
Route::resource('category', 'CategoryController', ['except' => [
    'create', 'edit'
]]);

/*
|--------------------------------------------------------------------------
| Newsletter
|--------------------------------------------------------------------------
*/
Route::resource('newsletter', 'NewsletterController', ['except' => [
    'create', 'edit'
]]);

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/
Route::resource('redirect', 'RedirectController', ['except' => [
    'create', 'edit'
]]);

Route::get('r/{code}', 'RedirectController@show');

Route::get('/', function () {
    return "<pre>Enjoy the silence.</pre>";
});