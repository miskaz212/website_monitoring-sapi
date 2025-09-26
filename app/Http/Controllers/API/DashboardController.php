<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\Kandang;
use App\Models\Sapi;
use App\Models\SensorReading;

class DashboardController extends Controller
{
    public function index()
    {
        $kandangs = Kandang::with(['sapis','latestSensorReading','jadwalPakans.pakan'])->get();
        $totalSapi = Sapi::count();
        $totalKandang = Kandang::count();

        $latest = SensorReading::latest()->first();

        return view('dashboard', compact('kandangs','totalSapi','totalKandang','latest'));
    }

    public function kandangDetail($id)
    {
        $kandang = Kandang::with([
            'sapis',
            'jadwalPakans.pakan',
            'sensorReadings' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(10);
            }
        ])->findOrFail($id);

        // Hitung total berat semua sapi di kandang
        $totalBeratSapi = $kandang->sapis->sum('berat_sapi');
        
        // Hitung kebutuhan pakan = 10% dari total berat sapi
        $persentasePakan = 0.10; // 10%
        $kebutuhanPakan = $totalBeratSapi * $persentasePakan;

        // ambil data sensor terakhir
        $latestSensor = $kandang->sensorReadings()->latest()->first();

        return view('kandang.detail', compact('kandang', 'kebutuhanPakan', 'latestSensor', 'totalBeratSapi'));
    }
}