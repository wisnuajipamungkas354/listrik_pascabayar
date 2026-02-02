<?php

namespace App\Filament\Resources\Pembayarans\Pages;

use App\Filament\Resources\Pembayarans\PembayaranResource;
use App\Models\Tagihan;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class ManagePembayarans extends ManageRecords
{
    protected static string $resource = PembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah')
                ->icon('heroicon-o-plus')
                ->modalHeading('Tambah Data Tagihan')
                ->modalSubmitActionLabel('Tambah')
                ->createAnotherAction(fn(Action $action) => $action->label('Simpan dan Tambah Lagi'))
                ->modalCancelActionLabel('Batal')
                ->using(function(array $data, string $model): Model {
                    $data['user_id'] = auth()->user()->id;

                    Tagihan::where('id', $data['tagihan_id'])->update(['status' => 'LUNAS']);
                    return $model::create($data);
                })
                ->successNotificationMessage('Pembayaran Berhasil!'),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Pembayaran';
    }
}
