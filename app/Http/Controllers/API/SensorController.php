<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SensorReading;
use App\Models\Kandang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SensorController extends Controller
{
public function create()
{
    $kandangs = \App\Models\Kandang::all();
    $sapis = \App\Models\Sapi::with('kandang')->get();
    $latest = \App\Models\SensorReading::latest()->first();

    return view('sensor.create', compact('kandangs', 'sapis', 'latest'));
}

    // Simpan data dari ESP8266 / ESP32
 public function store(Request $request)
    {
        // Validasi data
        $data = $request->validate([
            'suhu' => 'required|numeric',
            'sapi_id' => 'nullable|exists:sapis,id',
            'kandang_id' => 'nullable|exists:kandangs,id',
        ]);

        // Simpan data
        $reading = SensorReading::create($data);

        return response()->json([
            'message' => 'Data suhu berhasil disimpan',
            'data' => $reading
        ], 201);
    }
public function storeManual(Request $request)
{
    $validated = $request->validate([
        'kandang_id' => 'required|exists:kandangs,id',
        'sapi_id'    => 'nullable|exists:sapis,id', // opsional
        'suhu'       => 'required|numeric',
        'berat'      => 'required|numeric',
    ]);

    SensorReading::create([
        'kandang_id' => $validated['kandang_id'],
        'sapi_id'    => $validated['sapi_id'] ?? null,
        'suhu'       => $validated['suhu'],
        'berat'      => $validated['berat'],
        'waktu_baca' => now(),
    ]);

    return redirect()->route('dashboard')->with('success', 'Data sensor berhasil disimpan.');
}


// Ambil data terakhir
public function latest()
    {
        $reading = SensorReading::latest()->first();

        return response()->json([
            'status' => 'success',
            'data'   => $reading
        ]);
}

// mode khusus ketika klik dari detail sapi
public function createForSapi($id)
{
    $sapi = \App\Models\Sapi::with('kandang')->findOrFail($id);
    $latest = $sapi->sensorReadings()->latest()->first();

    return view('sensor.create', [
        'sapi' => $sapi,
        'latest' => $latest
    ]);
}

}
