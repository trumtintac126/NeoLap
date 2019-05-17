<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//read file csv import data
Route::post('orders', 'OrderController@uploadFile');
//get revenue-month-year
Route::get('orders-year','OrderController@revenueByMonth');
//get top product
Route::get('orders-top-product','OrderController@topBySell');
