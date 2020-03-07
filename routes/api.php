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
Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', 'AuthController@login');
    Route::post('/signup', 'AuthController@signup');
    Route::get('/logout', 'AuthController@logout');
});

Route::group(['middleware' => 'auth:api'], function() {
    Route::group(['prefix' => 'users'], function () {
    Route::get('/{id}', 'UserController@get');
        //Route::post('/', 'UserController@store');
        //Route::post('/download', 'UserController@download');
        Route::post('/upload_document', 'UserController@uploadDocument');
        //Route::put('/', 'UserController@update');
    });
});
