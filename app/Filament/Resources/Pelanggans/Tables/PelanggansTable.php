<?php

namespace App\Filament\Resources\Pelanggans\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PelanggansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID Pelanggan')
                    ->searchable(),
                TextColumn::make('username')
                    ->searchable(),
                TextColumn::make('nomor_kwh')
                    ->label('Nomor KwH')
                    ->searchable(),
                TextColumn::make('nama_pelanggan')
                    ->label('Nama Pelanggan')
                    ->searchable(),
                TextColumn::make('tarif.daya')
                    ->label('Daya')
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
                    ->modalDescription('Apakah kamu yakin akan menghapus data ini?')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->modalCancelActionLabel('Batal')
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Tidak Ada Data')
            ->emptyStateDescription('Tambahkan data pelanggan pertamamu, nanti akan muncul disini!')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Tambah Pelanggan')
                    ->url(route('filament.admin.resources.pelanggans.create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ]);
    }
}
