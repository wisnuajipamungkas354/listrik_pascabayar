<?php

namespace App\Filament\Resources\Tarifs\Pages;

use App\Filament\Resources\Tarifs\TarifResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageTarifs extends ManageRecords
{
    protected static string $resource = TarifResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Tarif')
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Data Tarif';
    }
}
