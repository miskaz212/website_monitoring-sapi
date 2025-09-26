<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('sensor_readings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('kandang_id')->nullable()->constrained('kandangs')->onDelete('cascade');
    $table->foreignId('sapi_id')->nullable()->constrained('sapis')->cascadeOnDelete();
    $table->decimal('suhu', 5, 2);
    // $table->decimal('berat', 8, 2);
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('sensor_readings');
    }
};
