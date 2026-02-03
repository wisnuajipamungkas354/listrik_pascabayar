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
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;

class TagihanAktifWidget extends TableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Tagihan::with('penggunaan')->whereHas('penggunaan', fn(Builder $query) => $query->where('pelanggan_id', auth('pelanggan')->user()->id))->orderBy('status', 'ASC'))
            ->columns([
                TextColumn::make('penggunaan.bulan')
                    ->label('Bulan'),

                TextColumn::make('penggunaan.tahun')
                    ->label('Tahun'),

                TextColumn::make('jumlah_meter')
                    ->label('Pemakaian')
                    ->suffix(' kWH'),

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
                    ->visible(fn(Tagihan $record) => $record->status === 'BELUM_BAYAR')
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
                        MidtransConfig::$serverKey = config('services.midtrans.server_key');
                        MidtransConfig::$isProduction = config('services.midtrans.is_production');
                        MidtransConfig::$isSanitized = config('services.midtrans.is_sanitized');
                        MidtransConfig::$is3ds = config('services.midtrans.is_3ds');
                    
                        if (empty(MidtransConfig::$serverKey)) {
                            \Filament\Notifications\Notification::make()
                                ->title('Konfigurasi Error')
                                ->body('Server Key Midtrans belum diatur di .env')
                                ->danger()
                                ->send();
                            return;
                        }

                        $params = [
                            'transaction_details' => [
                                'order_id' => 'TRX-' . $record->id . '-' . time(),
                                'gross_amount' => (int) $data['total_bayar'], 
                            ],
                            'customer_details' => [
                                'first_name' => auth('pelanggan')->user()->nama_pelanggan,
                            ],
                            'item_details' => [
                                [
                                    'id' => 'BILL-' . $record->id,
                                    'price' => (int) $record->total_tagihan,
                                    'quantity' => 1,
                                    'name' => 'Tagihan Listrik ' . $record->penggunaan->bulan . '/' . $record->penggunaan->tahun,
                                ],
                                [
                                    'id' => 'ADM',
                                    'price' => (int) $data['biaya_admin'],
                                    'quantity' => 1,
                                    'name' => 'Biaya Admin',
                                ],
                            ],
                        ];
                    
                        try {
                            $snapToken = Snap::getSnapToken($params);

                            // Kirim event ke browser agar JavaScript memunculkan popup Snap
                            $this->dispatch('open-midtrans-snap', snapToken: $snapToken);
                    
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Gagal memproses pembayaran')
                                ->danger()
                                ->body($e->getMessage())
                                ->send();
                        }
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
