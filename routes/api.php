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
    Route::group(['prefix' => 'users'], function() {
        Route::get('/{id}', 'UserController@get');
        //Route::post('/', 'UserController@store');
        Route::post('/upload_document', 'UserController@uploadDocument');
        //Route::put('/', 'UserController@update');
    });

    Route::group(['prefix' => 'properties'], function() {
        Route::post('/', 'PropertyController@store');
        Route::get('/{id}', 'PropertyController@get');
        Route::get('/user/{id}', 'PropertyController@getUserProperties');
        Route::get('/predetermined/{id}', 'PropertyController@setPredetermined');
        Route::get('/user-predetermined/{id}', 'PropertyController@getPredetermined');
    });

    Route::group(['prefix' => 'properties-types'], function() {
        Route::get('/', 'PropertyTypeController@getAll');
        //Route::post('/', 'PropertyController@store');
        //Route::get('/{id}', 'PropertyController@get');
    });

    Route::group(['prefix' => 'general-settings'], function() {
        Route::get('/list', 'GeneralSettingsController@listAll');
        Route::get('/key/{key}', 'GeneralSettingsController@getByKey');
    });

    Route::group(['prefix' => 'payment-methods'], function() {
        Route::post('/', 'PaymentMethodsController@store');
        Route::get('/user-cards/{id}', 'PaymentMethodsController@listCards');
        Route::get('/predetermined/{id}', 'PaymentMethodsController@setPredetermined');
        Route::get('/user-predetermined/{id}', 'PaymentMethodsController@getPredetermined');
    });

    Route::group(['prefix' => 'services'], function() {
        Route::post('/', 'ServiceController@store');
        Route::get('/{id}', 'ServiceController@get');
        Route::get('/list-scheduled/{id}', 'ServiceController@listScheduled');
        Route::get('/list-history/{id}', 'ServiceController@listHistory');
        Route::delete('/cancel/{id}', 'ServiceController@cancel');
    });
});
