<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pelanggan extends Authenticatable implements FilamentUser, HasName
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

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return $panel->getId() === 'pelanggan';
    }

    public function getFilamentName(): string
    {
        return $this->nama_pelanggan;
    }
}
