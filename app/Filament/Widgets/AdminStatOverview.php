<?php

namespace App\Filament\Widgets;

use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Penggunaan;
use App\Models\Tagihan;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {        
        return [
            Stat::make('User', User::count()),
            Stat::make('Pelanggan', Pelanggan::count()),
            Stat::make('Penggunaan Listrik', Tagihan::query()->sum('jumlah_meter') . ' kWH'),
            Stat::make('Belum Dibayar', Tagihan::query()->where('status', 'BELUM_DIBAYAR')->count()),
            Stat::make('Total Pembayaran', 'Rp' . number_format(Pembayaran::sum('total_bayar'), 2, ',','.'))
        ];
    }
}
