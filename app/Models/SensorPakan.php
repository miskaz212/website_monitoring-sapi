<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorPakan extends Model
{
    use HasFactory;

    protected $fillable = ['sapi_id', 'berat'];

    public function sapi()
    {
        return $this->belongsTo(Sapi::class);
    }
}