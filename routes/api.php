<?php

use App\Http\Controllers\API\GpsController;

Route::post('/gps/save', [GpsController::class, 'save']);
Route::get('/gps/getLastLocation', [GPSController::class, 'getLastLocation']);