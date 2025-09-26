<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\SensorController;
use App\Http\Controllers\SensorPakanController;
use App\Http\Controllers\API\SensorReadingController;

Route::post('/sensor_readings', [SensorController::class, 'store']);


Route::post('/sensor', [SensorController::class, 'store']);

Route::post('/sensor-pakan', [SensorPakanController::class, 'store']);
Route::get('/sensor/latest/{kandangId?}', [SensorController::class, 'latest']);
