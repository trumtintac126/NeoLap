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
//register
Route::post('/users', 'Api\UserController@register');
//register user for API
Route::post('/registerAPI', 'Api\UserController@registerAPI');
//login
Route::post('/login', 'Api\UserController@login');
//group user
Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('/users', 'Api\UserController@getAll');
    Route::put('/users/{id}', 'Api\UserController@update');
    Route::post('/logout', 'Api\UserController@logout');
    Route::delete('/users/{id}', 'Api\UserController@delete');
});
/****************************************************
 ***************** Router: Table_name ****************
 *****************************************************/
Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('/table_names', 'Api\TablenameController@create');
    Route::put('/table_names/{id}', 'Api\TablenameController@update');
    Route::get('/table_names/{id}', 'Api\TablenameController@finnById');
    Route::delete('/table_names/{id}', 'Api\TablenameController@delete');
    Route::get('/table_names', 'Api\TablenameController@all');
    Route::get('/get_tablename', 'Api\TablenameController@getTableName');
});
/****************************************************
 ***************** Router: Row_name ****************
 *****************************************************/
Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('/row_names', 'Api\RownameController@create');
    Route::put('/row_names/{table_name_id}/{row_name_id}', 'Api\RownameController@update');
    Route::get('/row_names/{table_name_id}', 'Api\RownameController@paginate');
    Route::delete('/row_names/{table_name_id}/{row_name_id}', 'Api\RownameController@delete');
});
/****************************************************
 ***************** Router: Row_value ****************
 *****************************************************/
Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('/row_values', 'Api\RowvalueController@create');
    Route::delete('/row_values/{id}', 'Api\RowvalueController@delete');
    Route::get('/row_values', 'Api\RowvalueController@all');
});

/****************************************************
 ***************** Router: API create with token ****************
 *****************************************************/

Route::post('/create_table', 'Api\ApiAuthenTokenController@createTable');
Route::post('/create_row_names', 'Api\ApiAuthenTokenController@creatRowName');
Route::post('/create_row_values', 'Api\ApiAuthenTokenController@createRowValue');

/****************************************************
 ***************** Router: API update with token ****************
 *****************************************************/
Route::put('/update_table/{table_id}', 'Api\ApiAuthenTokenUpdateController@updateTableName');
Route::put('/update_row_name/{table_id}/{row_name_id}', 'Api\ApiAuthenTokenUpdateController@updateRowName');
