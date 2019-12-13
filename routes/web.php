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
    return view('home');
});
Route::get('/three', function () {
    return view('three');
});
Route::get('/gradient', function () {
    return view('gradient');
});
Route::get('/grad2', function () {
    return view('grad2');
});
Route::get('/grad3', function () {
    return view('grad3');
});

