<?php

namespace App\Filament\Resources\Penggunaans\Pages;

use App\Filament\Resources\Penggunaans\PenggunaanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManagePenggunaans extends ManageRecords
{
    protected static string $resource = PenggunaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Data Penggunaan Listrik';
    }
}
