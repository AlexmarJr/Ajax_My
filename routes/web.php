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

Route::get('/','ProductController@index')->name('index'); 

Route::post('/create/product', 'ProductController@create')->name('create.product');
Route::post('/update/product', 'ProductController@update')->name('create.update');
Route::get('/get/product/{id}', 'ProductController@show')->name('show.product');
Route::get('/delete/product/{id}', 'ProductController@destroy')->name('destroy.product');
