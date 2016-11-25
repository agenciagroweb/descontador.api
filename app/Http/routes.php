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
| Users
|--------------------------------------------------------------------------
*/
Route::resource('user', 'UserController', ['except' => [
    'create', 'edit'
]]);

Route::post('user/{id}/picture', 'UserPictureController@update');
Route::delete('user/{id}/picture', 'UserPictureController@destroy');

Route::get('/', function () {
    return "<pre>Enjoy the silence.</pre>";
});
