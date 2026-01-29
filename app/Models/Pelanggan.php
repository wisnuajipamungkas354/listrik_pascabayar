<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pelanggan extends Model
{
    protected $fillable = [
        'username',
        'password',
        'nomor_kwh',
        'nama_pelanggan',
        'alamat',
        'tarif_id'
    ];

    protected $hidden = ['password'];

    public function tarif(): BelongsTo
    {
        return $this->belongsTo(Tarif::class);
    }

    public function penggunaans(): HasMany
    {
        return $this->hasMany(Penggunaan::class);
    }

    public function tagihans(): HasMany
    {
        return $this->hasMany(Tagihan::class);
    }

    public function pembayarans(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }
}
