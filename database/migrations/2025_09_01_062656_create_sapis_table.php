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
        Schema::create('sapis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kandang_id')->constrained('kandangs')->onDelete('cascade');
            $table->string('jenis_sapi')->nullable();
            $table->enum('jenis_kelamin', ['jantan', 'betina']);
            $table->date('tanggal_lahir')->nullable();
            $table->decimal('berat_sapi', 8, 2)->nullable();
            $table->enum('status', ['sehat', 'sakit', 'karantina'])->default('sehat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sapis');
    }
};