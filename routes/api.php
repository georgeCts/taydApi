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

    Route::post('/send-verification', 'AuthController@sendVerificationCode');
    Route::post('/confirm-verification', 'AuthController@confirmVerificationCode');
});
Route::group(['prefix' => 'channels'], function() {
    Route::post('/auth', 'ChannelsController@auth');
});

Route::group(['middleware' => 'auth:api'], function() {

    Route::group(['prefix' => 'users'], function() {
        Route::get('/{id}', 'UserController@get');
        Route::post('/upload_document', 'UserController@uploadDocument');
        //Route::put('/', 'UserController@update');

        Route::get('/first-login-tayder/{id}', 'UserController@updateFirstLoginTayder');

        Route::get('/{id}/coupons', 'UserController@getCoupons');
        Route::post('/{id}/coupons', 'UserController@setCoupons');
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

    Route::group(['prefix' => 'vehicles'], function() {

    });

    Route::group(['prefix' => 'vehicles-types'], function() {
        Route::get('/', 'VehicleTypeController@getAll');
    });

    Route::group(['prefix' => 'general-settings'], function() {
        Route::get('/list', 'GeneralSettingsController@listAll');
        Route::get('/key/{key}', 'GeneralSettingsController@getByKey');
    });

    Route::group(['prefix' => 'payment-methods'], function() {
        Route::get('/{id}', 'PaymentMethodsController@get');
        Route::post('/', 'PaymentMethodsController@store');
        Route::get('/user-cards/{id}', 'PaymentMethodsController@listCards');
        Route::get('/predetermined/{id}', 'PaymentMethodsController@setPredetermined');
        Route::get('/user-predetermined/{id}', 'PaymentMethodsController@getPredetermined');
    });

    Route::group(['prefix' => 'services'], function() {
        Route::post('/', 'ServiceController@store');
        Route::get('/{id}', 'ServiceController@get');
        Route::post('/accept', 'ServiceController@acceptService');
        Route::post('/start', 'ServiceController@startService');
        Route::post('/finish', 'ServiceController@finishService');
        Route::post('/cancel', 'ServiceController@cancel');
        Route::post('/rate-service', 'ServiceController@rateService');
        Route::get('/list-scheduled/{id}', 'ServiceController@listScheduled');
        Route::get('/list-tayder-scheduled/{id}', 'ServiceController@listTayderScheduled');
        Route::get('/list-history/{id}', 'ServiceController@listHistory');
        Route::get('/list-tayder-history/{id}', 'ServiceController@listTayderHistory');
        Route::get('/earnings/{id}', 'ServiceController@getEarnings');
    });

    Route::group(['prefix' => 'chat'], function() {
        Route::get('/{id}', 'ChatController@getMessages');
        Route::post('/new-message', 'ChatController@storeMessage');
    });

    Route::group(['prefix' => 'coupons'], function() {
        Route::post('/', 'CouponController@store');
        Route::put('/', 'CouponController@store');
        Route::get('/{id}', 'CouponController@get');
        Route::delete('/cancel/{id}', 'CouponController@cancel');
    });
});
