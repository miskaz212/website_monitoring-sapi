<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\SapiController;
use App\Http\Controllers\KandangController;
use App\Http\Controllers\API\SensorController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/kandang/{id}', [DashboardController::class, 'kandangDetail'])->name('kandang.detail');
Route::get('/sapi/{id}', [SapiController::class, 'show'])->name('sapi.detail');
Route::post('/sapi/store', [SapiController::class, 'store'])->name('sapi.store');
Route::get('/sapi/{id}', [SapiController::class, 'show'])->name('sapi.show');
Route::post('/sensor/manual', [SensorController::class, 'storeManual'])->name('sensor.storeManual');
Route::get('/sensor/create', [SensorController::class, 'create'])->name('sensor.create');
Route::post('/sensor/store', [SensorController::class, 'storeManual'])->name('sensor.storeManual');
Route::post('/kandang/store', [KandangController::class, 'store'])->name('kandang.store');
Route::get('/sapi/{id}/sensor/create', [SensorController::class, 'createForSapi'])->name('sensor.createForSapi');
Route::get('/sapi/{id}/qr', function ($id) {
    $sapi = \App\Models\Sapi::findOrFail($id);
    $data = url('/sapi/'.$sapi->id); // isi QR → link detail sapi

    return view('sapi.qr', compact('sapi', 'data'));
})->name('sapi.qr');

