<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SensorReading;
use App\Models\SensorPakan;

class Sapi extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_sapi',
        'kandang_id',
        'jenis_sapi',
        'jenis_kelamin',
        'tanggal_lahir',
        'berat_sapi',
        'status'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // Relasi ke kandang
    public function kandang()
    {
        return $this->belongsTo(Kandang::class);
    }

    // Relasi ke sensor readings lewat kandang
  public function sensorReadings()
    {
        return $this->hasMany(SensorReading::class);
    }

    // Ambil sensor reading terbaru
    public function latestSensorReading()
    {
        return $this->hasOne(SensorReading::class)->latestOfMany();
    }
    // Relasi ke pakan
    public function pakans()
    {
        return $this->hasMany(SensorPakan::class);
    }

    // Relasi pakan terbaru
    public function latestPakan()
    {
        return $this->hasOne(SensorPakan::class)->latestOfMany();
    }
}
