<?php

namespace App\Filament\Resources\Penggunaans;

use App\Filament\Resources\Penggunaans\Pages\ManagePenggunaans;
use App\Models\Penggunaan;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PenggunaanResource extends Resource
{
    protected static ?string $model = Penggunaan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Penggunaan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('pelanggan_id')
                    ->relationship('pelanggan', 'id')
                    ->required(),
                TextInput::make('bulan')
                    ->required()
                    ->numeric(),
                TextInput::make('tahun')
                    ->required()
                    ->numeric(),
                TextInput::make('meter_awal')
                    ->required()
                    ->numeric(),
                TextInput::make('meter_akhir')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('pelanggan.id')
                    ->label('Pelanggan'),
                TextEntry::make('bulan')
                    ->numeric(),
                TextEntry::make('tahun')
                    ->numeric(),
                TextEntry::make('meter_awal')
                    ->numeric(),
                TextEntry::make('meter_akhir')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Penggunaan')
            ->columns([
                TextColumn::make('pelanggan.id')
                    ->searchable(),
                TextColumn::make('bulan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tahun')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('meter_awal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('meter_akhir')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Tidak Ada Data')
            ->emptyStateDescription('Kamu bisa menambahkan data pertamamu dengan mengklik tombol Tambah');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePenggunaans::route('/'),
        ];
    }
}
