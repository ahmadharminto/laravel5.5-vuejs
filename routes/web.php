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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'data'], function() {
    Route::resource('/task', 'TaskController');
    Route::post('/send_chat', 'ChatController@send');
    Route::post('/save_chat', 'ChatController@saveToSession');
    Route::get('/chat_messages', 'ChatController@messages');
    Route::post('/clear_chat', 'ChatController@destroySession');
});

Route::get('/{vue_capture?}', 'VueController@index')->where('vue_capture', '[\/\w\.-]*');


