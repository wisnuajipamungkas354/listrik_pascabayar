<?php

namespace App\Filament\Pelanggan\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use App\Models\Pelanggan;
use App\Models\Tarif;
use Filament\Auth\Pages\Register;
use Filament\Schemas\Schema;

class PelangganRegistration extends Register
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('username')
                    ->required()
                    ->minLength(8)
                    ->unique(Pelanggan::class, 'username'),

                \Filament\Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->minLength(6),

                \Filament\Forms\Components\TextInput::make('password_confirmation')
                    ->password()
                    ->required()
                    ->same('password'),

                \Filament\Forms\Components\TextInput::make('nomor_kwh')
                    ->required()
                    ->unique(Pelanggan::class, 'nomor_kwh'),

                \Filament\Forms\Components\TextInput::make('nama_pelanggan')
                    ->required(),

                \Filament\Forms\Components\Textarea::make('alamat')
                    ->required(),

                \Filament\Forms\Components\Select::make('tarif_id')
                    ->label('Daya')
                    ->options(Tarif::pluck('daya', 'id'))
                    ->required(),
            ]);
    }
    
    protected function handleRegistration(array $data): Pelanggan
    {
        return Pelanggan::create([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'nomor_kwh' => $data['nomor_kwh'],
            'nama_pelanggan' => $data['nama_pelanggan'],
            'alamat' => $data['alamat'],
            'tarif_id' => $data['tarif_id'],
        ]);
    }
}
