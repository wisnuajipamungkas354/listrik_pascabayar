<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penggunaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggans')->cascadeOnDelete()->cascadeOnUpdate();
            $table->tinyInteger('bulan');
            $table->year('tahun');
            $table->integer('meter_awal');
            $table->integer('meter_akhir');
            $table->timestamps();
            $table->unique(['pelanggan_id', 'bulan', 'tahun']);
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggunaans');
    }
};
