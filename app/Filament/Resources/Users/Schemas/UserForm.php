<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Admin')
                    ->required(),
                TextInput::make('username')
                    ->label('Username')
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'Username ini sudah terdaftar.',
                    ])
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->required(),
                Select::make('level_id')
                    ->relationship('level', 'nama_level')
            ]);
    }
}
