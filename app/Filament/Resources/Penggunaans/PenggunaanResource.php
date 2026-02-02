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

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Bolt;

    protected static ?string $navigationLabel = 'Penggunaan Listrik';

    protected static ?string $recordTitleAttribute = 'Penggunaan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('pelanggan_id')
                    ->relationship('pelanggan', 'nama_pelanggan')
                    ->required(),
                TextInput::make('bulan')
                    ->placeholder('Masukkan Bulan')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(12)
                    ->default(date('m')),
                TextInput::make('tahun')
                    ->placeholder('Masukkan Tahun')
                    ->required()
                    ->numeric()
                    ->minValue(2000)
                    ->default(date('Y')),
                TextInput::make('meter_awal')
                    ->placeholder('Masukkan Meter Awal')
                    ->required()
                    ->numeric()
                    ->suffix('kWH'),
                TextInput::make('meter_akhir')
                    ->placeholder('Masukkan Meter Akhir')
                    ->required()
                    ->numeric()
                    ->suffix('kWH'),
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
                TextColumn::make('id')
                    ->label('ID Penggunaan')
                    ->searchable(),
                TextColumn::make('pelanggan.id')
                    ->label('ID Pelanggan')
                    ->searchable(),
                TextColumn::make('bulan')
                    ->formatStateUsing(fn(string $state, Penggunaan $record) => date('M', strtotime($record->tahun . '-' . $state . '-' . '01')))
                    ->sortable(),
                TextColumn::make('tahun')
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
                ViewAction::make()
                    ->label('Detail'),
                EditAction::make(),
                DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus')
                    ->modalDescription('Apakah kamu yakin data ini akan dihapus?')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->modalCancelActionLabel('Batal')
                    ->successNotificationMessage('Berhasil dihapus!'),
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
