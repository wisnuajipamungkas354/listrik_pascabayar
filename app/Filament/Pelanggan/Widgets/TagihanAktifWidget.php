<?php

namespace App\Filament\Pelanggan\Widgets;

use App\Models\Pembayaran;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Tagihan;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class TagihanAktifWidget extends TableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Tagihan::with('penggunaan')->whereHas('penggunaan', fn(Builder $query) => $query->where('pelanggan_id', auth('pelanggan')->user()->id))->where('status', 'BELUM_BAYAR'))
            ->columns([
                TextColumn::make('penggunaan.bulan')
                    ->label('Bulan'),

                TextColumn::make('penggunaan.tahun')
                    ->label('Tahun'),

                TextColumn::make('total_tagihan')
                    ->label('Total')
                    ->money('IDR'),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'danger' => 'BELUM_BAYAR',
                        'success' => 'LUNAS',
                    ]),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                Action::make('bayar')
                    ->label('Bayar')
                    ->icon('heroicon-o-credit-card')
                    ->color('success')
                    ->modalHeading('Konfirmasi Pembayaran')
                    ->modalSubmitActionLabel('Bayar Sekarang')
                    ->form([
                        Placeholder::make('periode')
                            ->label('Periode')
                            ->content(fn (Tagihan $record) =>
                                $record->penggunaan->bulan . '/' . $record->penggunaan->tahun
                            ),

                        Placeholder::make('total_tagihan')
                            ->label('Total Tagihan')
                            ->content(fn (Tagihan $record) =>
                                'Rp ' . number_format($record->total_tagihan, 0, ',', '.')
                            ),

                        TextInput::make('biaya_admin')
                            ->label('Biaya Admin')
                            ->numeric()
                            ->default(2500)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get, Tagihan $record) {
                                $set(
                                    'total_bayar',
                                    $record->total_tagihan + ($get('biaya_admin') ?? 0)
                                );
                            }),

                        TextInput::make('total_bayar')
                            ->label('Total Bayar')
                            ->disabled()
                            ->numeric()
                            ->prefix('Rp')
                            ->dehydrated()
                            ->reactive(),
                    ])
                    ->action(function (array $data, Tagihan $record) {
                        // SEMENTARA (nanti ganti Midtrans)
                        // dd($data, $record);
                
                        // contoh simpan pembayaran
                        Pembayaran::create([
                            'tagihan_id'         => $record->id,
                            'tanggal_pembayaran' => date('Y-m-d'),
                            'biaya_admin'        => $data['biaya_admin'],
                            'total_bayar'        => $data['total_bayar'],
                            'metode_pembayaran'  => 'TRANSFER'
                        ]);

                        $record->update(['status' => 'LUNAS']);
                        $record->save();
                    })
                    ->successNotificationMessage('Pembayaran Berhasil!')
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ])
            ->emptyStateHeading('Tidak ada tagihan aktif');
    }
}
