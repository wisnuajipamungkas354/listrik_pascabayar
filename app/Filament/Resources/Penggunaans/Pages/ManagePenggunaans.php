<?php

namespace App\Filament\Resources\Penggunaans\Pages;

use App\Filament\Resources\Penggunaans\PenggunaanResource;
use App\Models\Penggunaan;
use App\Models\Tagihan;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManagePenggunaans extends ManageRecords
{
    protected static string $resource = PenggunaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah')
                ->icon('heroicon-o-plus')
                ->modalHeading('Tambah Data Penggunaan')
                ->modalSubmitActionLabel('Tambah')
                ->createAnotherAction(fn(Action $action) => $action->label('Simpan dan Tambah Lagi'))
                ->modalCancelActionLabel('Batal')
                ->successNotificationTitle('Data berhasil ditambahkan!')
                ->before(function(CreateAction $action, array $data) {
                    $checkDataExists = Penggunaan::where('pelanggan_id', $data['pelanggan_id'])->where('bulan', $data['bulan'])->where('tahun', $data['tahun'])->exists();
                    if($checkDataExists) {
                        Notification::make()
                            ->warning()
                            ->title('Data Penggunaan Sudah Ada!')
                            ->body('Silahkan cek di data penggunaan')
                            ->color('warning')
                            ->duration(5000)
                            ->send();
                            
                        $action->halt();
                    }
                }),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Data Penggunaan Listrik';
    }
}
