<?php

namespace App\Filament\Resources\Pelanggans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PelangganInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('username'),
                TextEntry::make('nomor_kwh'),
                TextEntry::make('nama_pelanggan'),
                TextEntry::make('alamat')
                    ->columnSpanFull(),
                TextEntry::make('tarif.id')
                    ->label('Tarif'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
