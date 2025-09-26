<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SensorReading; // ✅ tambahkan ini

class Kandang extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kandang', 
        'lokasi', 
        'kapasitas'
    ];

    public function sapis()
    {
        return $this->hasMany(Sapi::class);
    }

    public function jadwalPakans()
    {
        return $this->hasMany(JadwalPakan::class);
    }

    public function sensorReadings()
    {
        return $this->hasMany(SensorReading::class);
    }

    public function latestSensorReading()
    {
        return $this->hasOne(SensorReading::class)->latest();
    }
    
}
