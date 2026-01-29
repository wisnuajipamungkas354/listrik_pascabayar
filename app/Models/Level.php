<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    protected $fillable = ['nama_level'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
