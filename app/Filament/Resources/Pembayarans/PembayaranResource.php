<?php

namespace App\Filament\Resources\Pembayarans;

use App\Filament\Resources\Pembayarans\Pages\ManagePembayarans;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CreditCard;

    protected static ?string $navigationLabel = 'Pembayaran';

    protected static ?string $recordTitleAttribute = 'Pembayaran';

    public static function form(Schema $schema): Schema
    {
        $hitungTotal = function (Get $get, Set $set) {

            $tagihanId  = $get('tagihan_id');
            $admin      = (int) ($get('biaya_admin') ?? 0);
    
            if (!$tagihanId) return;
    
            $tagihan = Tagihan::find($tagihanId);
            if (!$tagihan) return;
    
            $set('total_bayar', $tagihan->total_tagihan + $admin);
        };

        return $schema
            ->components([
                Select::make('tagihan_id')
                    ->relationship('tagihan', 'id', modifyQueryUsing: function (Builder $query, $get) {
                        $currentId = $get('tagihan_id');
                        
                        return $query->where('status', 'BELUM_BAYAR')->whereDoesntHave('pembayaran')
                            ->orWhere('id', $currentId);
                    })
                    ->reactive()
                    ->preload()
                    ->afterStateUpdated($hitungTotal)
                    ->required(),
                DatePicker::make('tanggal_pembayaran')
                    ->default(date('Y-m-d'))
                    ->required(),
                TextInput::make('biaya_admin')
                    ->required()
                    ->prefix('Rp')
                    ->reactive()
                    ->afterStateUpdated($hitungTotal)
                    ->numeric(),
                TextInput::make('total_bayar')
                    ->required()
                    ->prefix('Rp')
                    ->disabled()
                    ->dehydrated()
                    ->numeric(),
                Select::make('metode_pembayaran')
                    ->options(['CASH' => 'Cash', 'TRANSFER' => 'Transfer'])
                    ->required(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tagihan.id')
                    ->label('Tagihan'),
                TextEntry::make('tanggal_pembayaran')
                    ->date('d-m-Y'),
                TextEntry::make('biaya_admin')
                    ->numeric(),
                TextEntry::make('total_bayar')
                    ->numeric(),
                TextEntry::make('metode_pembayaran')
                    ->badge(),
                TextEntry::make('user.name')
                    ->label('User')
                    ->placeholder('-'),
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
            ->recordTitleAttribute('Pembayaran')
            ->columns([
                TextColumn::make('id')
                    ->label('ID Pembayaran')
                    ->searchable(),
                TextColumn::make('tagihan.id')
                    ->label('ID Tagihan')
                    ->searchable(),
                TextColumn::make('tanggal_pembayaran')
                    ->date('d-m-Y')
                    ->sortable(),
                TextColumn::make('biaya_admin')
                    ->prefix('Rp')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_bayar')
                    ->prefix('Rp')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('metode_pembayaran')
                    ->badge(),
                TextColumn::make('user.name')
                    ->searchable(),
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
            'index' => ManagePembayarans::route('/'),
        ];
    }
}
