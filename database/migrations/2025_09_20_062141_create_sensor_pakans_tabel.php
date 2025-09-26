<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('sensor_pakans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('sapi_id')->constrained()->onDelete('cascade');
    $table->float('berat'); // berat pakan (kg)
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sensor_pakans');
    }
};
