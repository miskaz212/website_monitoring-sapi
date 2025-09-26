<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pakan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pakan',
        'jenis_pakan',
        'protein',
        'kalori_per_kg',
        'harga_per_kg',
        'deskripsi'
    ];

    public function jadwalPakans()
    {
        return $this->hasMany(JadwalPakan::class);
    }
}
