<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'kandang_id',
        'sapi_id',
        'suhu',
    ];

    // Relasi ke sapi
    public function sapi()
    {
        return $this->belongsTo(Sapi::class);
    }

    // Relasi ke kandang
    public function kandang()
    {
        return $this->belongsTo(Kandang::class);
    }
}
