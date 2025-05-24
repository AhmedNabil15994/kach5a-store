<?php


/*
|================================================================================
|                             Back-END ROUTES
|================================================================================
*/
Route::prefix('dashboard')->middleware(['dashboard.auth','permission:dashboard_access'])->group(function () {


    foreach (["delivery_times.php"] as $value) {
        require_once(module_path('DeliveryTime', 'Routes/Dashboard/' . $value));
    }
});

/*
|================================================================================
|                             FRONT-END ROUTES
|================================================================================
*/
Route::prefix('/')->middleware('site.activation')->group(function () {

});
