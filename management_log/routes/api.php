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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/****************************************************
 ***************** Router: User **********************
 *****************************************************/
//Route::get('users', ['as' => 'users_list', 'uses' => 'Api\UserController@getAll']);
Route::get('/users', 'Api\UserController@getAll');
Route::post('/users','Api\UserController@create');

