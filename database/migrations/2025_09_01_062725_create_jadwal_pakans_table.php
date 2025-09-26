<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('jadwal_pakans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kandang_id')->constrained('kandangs')->onDelete('cascade');
            $table->foreignId('pakan_id')->constrained('pakans')->onDelete('cascade');
            $table->time('waktu_pakan');
            $table->decimal('jumlah_kg', 8, 2);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_pakans');
    }
};

