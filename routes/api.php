<?php

use Illuminate\Support\Facades\Route;

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

Route::prefix('articles')->group(function () {
    Route::post('create', "ArticleController@store");
    Route::get('/', "ArticleController@index");
    Route::get('/{id}', "ArticleController@show");
    Route::get('status/{status}', "ArticleController@listByStatus");
    Route::put('edit/{id}', "ArticleController@update");
    Route::delete('{id}', "ArticleController@destroy");
});
