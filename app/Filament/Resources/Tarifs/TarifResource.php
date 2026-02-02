<?php

namespace App\Filament\Resources\Tarifs;

use App\Filament\Resources\Tarifs\Pages\ManageTarifs;
use App\Models\Tarif;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TarifResource extends Resource
{
    protected static ?string $model = Tarif::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Bolt;

    protected static ?string $navigationLabel = 'Tarif';

    protected static ?string $recordTitleAttribute = 'Tarif';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('daya')
                    ->label('Daya')
                    ->placeholder('Masukkan Daya')
                    ->suffix('KwH')
                    ->required()
                    ->numeric(),
                TextInput::make('tarifperkwh')
                    ->label('Tarif per KwH')
                    ->placeholder('Masukkan Tarif per KwH')
                    ->prefix('Rp')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('daya')
                    ->numeric(),
                TextEntry::make('tarifperkwh')
                    ->label('Tarif per KwH')
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
            ->recordTitleAttribute('Tarif')
            ->columns([
                TextColumn::make('id')
                    ->label('ID Tarif')
                    ->sortable(),
                TextColumn::make('daya')
                    ->numeric()
                    ->suffix(' watt')
                    ->sortable(),
                TextColumn::make('tarifperkwh')
                    ->label('Tarif per KwH')
                    ->numeric()
                    ->prefix('Rp')
                    ->alignEnd()
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
                    ->label('Detail')
                    ->modalHeading('Detail Tarif')
                    ->modalCancelAction(fn(Action $action) => $action->label('Tutup')),
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
            'index' => ManageTarifs::route('/'),
        ];
    }
}
