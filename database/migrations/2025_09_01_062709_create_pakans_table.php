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
        Schema::create('pakans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pakan');
            $table->string('jenis_pakan');
            $table->decimal('protein', 5, 2)->nullable();
            $table->decimal('kalori_per_kg', 8, 2)->nullable();
            $table->decimal('harga_per_kg', 10, 2)->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pakans');
    }
};
