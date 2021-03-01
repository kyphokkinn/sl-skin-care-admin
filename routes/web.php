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

Route::group([
    'prefix' => env('APP_API'),
    'namespace' => 'Api'
], function () {
    Route::post('/order_list_create', 'ApiOrderCreateController@create_order_with_items');
    Route::post('/push_notification_order', 'ApiOrderCreateController@push_order');

});