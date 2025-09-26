<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorPakan;

class SensorPakanController extends Controller
{
    // Menyimpan data sensor pakan
    public function store(Request $request)
    {
        $data = $request->validate([
            'sapi_id' => 'required|exists:sapis,id',
            'berat'   => 'required|numeric',
        ]);

        $sensor = SensorPakan::create($data);

        return response()->json([
            'message' => 'Data sensor berhasil disimpan',
            'data'    => $sensor,
        ]);
    }

    // Menampilkan semua data sensor pakan
    public function index()
    {
        return response()->json(SensorPakan::all());
    }

    // Menampilkan data sensor pakan berdasarkan ID
    public function show($id)
    {
        $sensor = SensorPakan::findOrFail($id);
        return response()->json($sensor);
    }
}
