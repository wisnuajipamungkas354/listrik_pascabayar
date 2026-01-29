<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tagihan extends Model
{
    protected $fillable = [
        'penggunaan_id',
        'pelanggan_id',
        'bulan',
        'tahun',
        'jumlah_meter',
        'status'
    ];

    public function penggunaan(): BelongsTo
    {
        return $this->belongsTo(Penggunaan::class);
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class);
    }
}
