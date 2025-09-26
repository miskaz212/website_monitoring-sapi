<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SensorReading;
use Carbon\Carbon;

class CleanupSensorData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sensor:cleanup {--days=7 : Hapus data lebih dari X hari}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus data sensor yang lebih dari 7 hari';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);
        
        $this->info("Menghapus data sensor lebih dari {$days} hari (sebelum {$cutoffDate->format('Y-m-d H:i:s')})...");
        
        // Hitung jumlah data yang akan dihapus
        $count = SensorReading::where('created_at', '<', $cutoffDate)->count();
        
        if ($count == 0) {
            $this->info('Tidak ada data yang perlu dihapus.');
            return 0;
        }
        
        $this->warn("Akan menghapus {$count} record data sensor.");
        
        if ($this->confirm('Lanjutkan penghapusan?')) {
            // Hapus data
            $deleted = SensorReading::where('created_at', '<', $cutoffDate)->delete();
            
            $this->info("✅ Berhasil menghapus {$deleted} record data sensor.");
            
            // Log aktivitas
            \Log::info("Cleanup sensor data: {$deleted} records deleted (older than {$days} days)");
        } else {
            $this->info('Penghapusan dibatalkan.');
        }
        
        return 0;
    }
}