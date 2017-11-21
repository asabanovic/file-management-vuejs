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
Route::auth();
Route::get('/', 'HomeController@index')->name('show-home');
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/attachments/store', 'HomeController@store')->name('store-attachments');
Route::post('/attachments', 'HomeController@pullAttachments')->name('pull-attachments');
Route::delete('/attachments/', 'HomeController@deleteAttachment')->name('delete-attachment');
Route::post('/attachments/categories', 'HomeController@getCategories')->name('pull-categories');

Auth::routes();


