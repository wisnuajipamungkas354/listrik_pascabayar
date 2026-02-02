<?php

namespace App\Filament\Resources\Pelanggans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PelangganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('username')
                    ->placeholder('Masukkan username')
                    ->required(),
                TextInput::make('password')
                    ->placeholder('Masukkan Password')
                    ->password()
                    ->revealable()
                    ->required(),
                TextInput::make('nomor_kwh')
                    ->label('Nomor KwH')
                    ->placeholder('Masukkan Nomor KwH')
                    ->required(),
                TextInput::make('nama_pelanggan')
                    ->label('Nama Pelanggan')
                    ->placeholder('Masukkan Nama Pelanggan')
                    ->required(),
                Textarea::make('alamat')
                    ->label('Alamat')
                    ->placeholder('Masukkan Alamat Lengkap')
                    ->required()
                    ->columnSpanFull(),
                Select::make('tarif_id')
                    ->label('Daya')
                    ->relationship('tarif', 'daya')
                    ->required(),
            ]);
    }
}
