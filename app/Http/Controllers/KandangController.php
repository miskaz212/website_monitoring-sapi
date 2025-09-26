<?php

namespace App\Http\Controllers;

use App\Models\Kandang;
use Illuminate\Http\Request;

class KandangController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_kandang' => 'required|string|max:100',
            'lokasi' => 'nullable|string|max:255',
            'kapasitas' => 'required|integer|min:1',
        ]);

        Kandang::create([
            'nama_kandang' => $request->nama_kandang,
            'lokasi' => $request->lokasi,
            'kapasitas' => $request->kapasitas,
        ]);

        return redirect()->back()->with('success', '✅ Kandang berhasil ditambahkan!');
    }
}
