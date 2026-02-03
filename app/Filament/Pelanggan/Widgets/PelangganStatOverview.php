<?php

namespace App\Filament\Pelanggan\Widgets;

use App\Models\Penggunaan;
use App\Models\Tagihan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class PelangganStatOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $pelanggan = auth('pelanggan')->user();

        $penggunaan = Penggunaan::query()
            ->with('tagihan')
            ->where('pelanggan_id', $pelanggan->id)
            ->whereHas('tagihan', fn ($q) => 
                $q->where('status', 'BELUM_BAYAR')
            )
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->first();

        if (! $penggunaan || ! $penggunaan->tagihan) {
            return [
                Stat::make('Tagihan Aktif', 'Tidak ada')
                    ->description('Semua tagihan sudah lunas')
                    ->color('success'),
            ];
        }

        $tagihan = $penggunaan->tagihan;

        return [
            Stat::make('Tagihan Aktif', 'Rp ' . number_format($tagihan->total_tagihan, 0, ',', '.'))
                ->color('danger')
                ->description("Tagihan listrik bulan {$penggunaan->bulan}/{$penggunaan->tahun}")
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
        ];
    }
}
