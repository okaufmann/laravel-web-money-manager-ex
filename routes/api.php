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

Route::any('services.php', 'MmexController@handle')->middleware(['api', 'ensureguid']);

Route::group(['prefix'     => 'api/v1',
              'middleware' => 'auth:api', ],
    function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::group(['prefix' => 'transactions'], function () {
            Route::get('', 'TransactionController@index');
            Route::post('', 'TransactionController@store');
        });

        Route::group(['prefix' => 'category'], function () {
            Route::get('{category}/subcategories', 'CategoryController@subCategories');
            Route::get('', 'CategoryController@index');
        });

        Route::group(['prefix' => 'payee'], function () {
            Route::get('', 'PayeeController@index');
            Route::post('', 'PayeeController@store');
        });
    });
