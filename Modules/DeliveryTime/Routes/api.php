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

Route::group(['prefix' => 'delivery_times', 'namespace' => 'WebService'], function () {

    Route::get('/', 'DeliveryTimeController@getDeliveryTimes')->name('api.get_delivery_times');
    Route::get('working-times', 'DeliveryTimeController@getWorkingTimes')->name('api.get_working_times');
});
