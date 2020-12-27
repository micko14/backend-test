<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::get('news', 'Api\NewsController@index');

// List single news
Route::get('news/{news}', 'Api\NewsController@show');

// Create new news
Route::post('news', 'Api\NewsController@store');

// Update news
Route::put('news/{news}', 'Api\NewsController@update');

// Delete news
Route::delete('news/{news}', 'Api\NewsController@destroy');


