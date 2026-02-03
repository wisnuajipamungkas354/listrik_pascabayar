<?php

namespace App\Filament\Pelanggan\Pages;

use App\Filament\Pelanggan\Widgets\PelangganStatOverview;
use App\Filament\Pelanggan\Widgets\TagihanAktifWidget;
use App\Models\Penggunaan;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Illuminate\Contracts\Support\Htmlable;

class DashboardPelanggan extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Home;
    protected static ?string $navigationLabel = 'Dashboard';
    
    protected string $view = 'filament.pelanggan.pages.dashboard-pelanggan';

    protected static bool $shouldRegisterNavigation = true;

    public function getTitle(): string|Htmlable
    {
        return 'Selamat Datang, ' . auth('pelanggan')->user()->nama_pelanggan;
    }

    public function getHeaderWidgets(): array
    {
        return [
            PelangganStatOverview::class,
            TagihanAktifWidget::class,
        ];
    }
}
