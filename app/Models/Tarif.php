<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tarif extends Model
{
    protected $fillable = ['daya', 'tarifperkwh'];

    public function pelanggans(): HasMany
    {
        return $this->hasMany(Pelanggan::class);
    }
}
