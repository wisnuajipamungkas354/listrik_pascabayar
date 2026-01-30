<?php

namespace App\Filament\Resources\Pelanggans\Pages;

use App\Filament\Resources\Pelanggans\PelangganResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreatePelanggan extends CreateRecord
{
    protected static string $resource = PelangganResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Tambah Pelanggan';
    }
}
