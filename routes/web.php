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

Route::get('/', function () {
    return view('contents.Index');
});

Route::get('/privacidad', function () {
    return view('contents.Privacidad');
});

Route::get('/terminos-condiciones', function () {
    return view('contents.TerminosCondiciones');
});
