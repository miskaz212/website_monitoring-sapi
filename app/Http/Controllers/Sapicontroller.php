<?php

namespace App\Http\Controllers;

use App\Models\Sapi;
use Illuminate\Http\Request;

class SapiController extends Controller
{
       public function show($id)
    {
        $sapi = Sapi::with('latestSensorReading')->findOrFail($id);

        $suhu = $sapi->latestSensorReading ? $sapi->latestSensorReading->suhu : 'Belum ada data';

        return view('sapi.detail', compact('sapi', 'suhu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_sapi'   => 'required|string|max:255',
            'jenis_kelamin'=> 'required|string',
            'tanggal_lahir'=> 'required|date',
            'berat_sapi'   => 'required|numeric',
            'status'       => 'required|string',
            'kandang_id'   => 'required|exists:kandangs,id',
        ]);

        Sapi::create($request->all());

        return redirect()->back()->with('success', 'Sapi berhasil ditambahkan!');
    }

    public function qr($id)
    {
        $sapi = Sapi::with('kandang')->findOrFail($id);

        // Data untuk QR code
        $data = json_encode([
            'id_sapi'       => $sapi->id,
            'nama_sapi'     => $sapi->nama_sapi ?? '',
            'kandang_id'    => $sapi->kandang->id ?? '',
            'kandang_nama'  => $sapi->kandang->nama_kandang ?? '',
            'jenis'         => $sapi->jenis_sapi ?? '',
            'kelamin'       => $sapi->jenis_kelamin ?? '',
            'berat'         => $sapi->berat_sapi ?? '',
        ]);

        return view('sapi.qr', compact('sapi', 'data'));
    }
    
}
