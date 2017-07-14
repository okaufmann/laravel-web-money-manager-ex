<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();
Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');
Route::pattern('id', '[0-9]+');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::resource('transactions', 'TransactionController');
    Route::get('settings', 'SettingsController@index');
    Route::post('settings', 'SettingsController@update');

    Route::group(['prefix' => '/admin'], function () {
        Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    });

    // must be at the end of the definitions
    Route::get('/{id?}', 'HomeController@index');
});
