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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tagihan_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('pelanggan_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('tanggal_pembayaran');
            $table->unsignedTinyInteger('bulan_bayar');
            $table->decimal('biaya_admin', 12, 2);
            $table->decimal('total_bayar', 12, 2);
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
