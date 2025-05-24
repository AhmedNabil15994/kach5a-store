<?php

Route::group(['prefix' => 'delivery_times'], function () {

    Route::get('/', 'Dashboard\DeliveryTimeController@index')
        ->name('dashboard.delivery_times.index');

    Route::put('/', 'Dashboard\DeliveryTimeController@update')
        ->name('dashboard.delivery_times.update');

});
