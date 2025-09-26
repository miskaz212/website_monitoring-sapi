<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPakan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kandang_id',
        'pakan_id',
        'waktu_pakan',
        'jumlah_kg',
        'status'
    ];

    protected $casts = [
        'waktu_pakan' => 'datetime:H:i'
    ];

    public function kandang()
    {
        return $this->belongsTo(Kandang::class);
    }

    public function pakan()
    {
        return $this->belongsTo(Pakan::class);
    }
}
