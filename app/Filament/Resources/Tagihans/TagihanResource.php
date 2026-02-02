<?php

namespace App\Filament\Resources\Tagihans;

use App\Filament\Resources\Tagihans\Pages\ManageTagihans;
use App\Models\Tagihan;
use App\Models\Penggunaan;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TagihanResource extends Resource
{
    protected static ?string $model = Tagihan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;

    protected static ?string $navigationLabel = 'Tagihan';

    protected static ?string $recordTitleAttribute = 'Tagihan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('penggunaan_id')
                    ->relationship('penggunaan', 'id')
                    ->searchable()
                    ->searchingMessage('Mencari data...')
                    ->noSearchResultsMessage('Penggunaan tidak ditemukan')
                    ->reactive()
                    ->preload()
                    ->required()
                    ->afterStateUpdated(function ($state, Set $set) {

                        if (!$state) return;
    
                        $penggunaan = Penggunaan::with('pelanggan.tarif')
                            ->find($state);
    
                        if (!$penggunaan) return;
    
                        $jumlahKwh = $penggunaan->meter_akhir - $penggunaan->meter_awal;
                        $tarif     = $penggunaan->pelanggan->tarif->tarifperkwh;
    
                        $set('jumlah_meter', $jumlahKwh);
                        $set('total_tagihan', $jumlahKwh * $tarif);
                    }),
                TextInput::make('jumlah_meter')
                    ->numeric()
                    ->placeholder('Masukkan jumlah meter')
                    ->suffix('kWH')
                    ->disabled()
                    ->dehydrated()
                    ->required(),
                TextInput::make('total_tagihan')
                    ->numeric()
                    ->placeholder('Masukkan total tagihan')
                    ->prefix('Rp')
                    ->disabled()
                    ->dehydrated()
                    ->required(),
                Select::make('status')
                    ->options(['BELUM_BAYAR' => 'BELUM BAYAR', 'LUNAS' => 'LUNAS'])
                    ->default('BELUM_BAYAR')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Tagihan')
            ->columns([
                TextColumn::make('penggunaan.id')
                    ->label('ID Penggunaan')
                    ->searchable(),
                TextColumn::make('penggunaan.pelanggan_id')
                    ->label('ID Pelanggan')
                    ->searchable(),
                TextColumn::make('jumlah_meter')
                    ->numeric()
                    ->suffix(' kWH')
                    ->sortable(),
                TextColumn::make('total_tagihan')
                    ->prefix('Rp')
                    ->formatStateUsing(fn(string $state) => number_format($state, 2,',', '.'))
                    ->alignEnd()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match($state) {
                        'BELUM_BAYAR' => 'danger',
                        'LUNAS' => 'success'
                    })
                    ->formatStateUsing(fn(string $state) => str_replace('_', ' ', $state)),
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
            'index' => ManageTagihans::route('/'),
        ];
    }
}
