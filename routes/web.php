<?php

use App\Http\Controllers\TagihanController;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Midtrans\Config as MidtransConfig;
use Midtrans\Notification;

Route::post('/midtrans-callback', function(Request $request) {
    MidtransConfig::$serverKey = config('services.midtrans.server_key');
    MidtransConfig::$isProduction = config('services.midtrans.is_production');

    try {
        $notif = new Notification();
        $transaction = $notif->transaction_status;
        $order_id = $notif->order_id;
        
        $tagihanId = explode('-', $order_id)[1];
        $tagihan = Tagihan::find($tagihanId);

        if (!$tagihan) {
            return response()->json(['message' => 'Tagihan tidak ditemukan'], 404);
        }

        if ($tagihan->status === 'LUNAS') {
            return response()->json(['message' => 'Sudah diproses sebelumnya'], 200);
        }

        DB::transaction(function () use ($transaction, $tagihan, $notif, $tagihanId) {
            
            if ($transaction == 'settlement' || $transaction == 'capture') {
                
                $tagihan->update(['status' => 'LUNAS']);

                Pembayaran::updateOrCreate(
                    ['tagihan_id' => $tagihanId],
                    [
                        'tanggal_pembayaran' => now(),
                        'biaya_admin' => 2500,
                        'total_bayar' => $notif->gross_amount,
                        'metode_pembayaran' => $notif->payment_type ?? 'MIDTRANS',
                    ]
                );

            } elseif ($transaction == 'pending') {
                $tagihan->update(['status' => 'BELUM_BAYAR']);
            }
        });

        // Selalu return 200 agar Midtrans tidak mengirim ulang notifikasi
        return response()->json(['message' => 'Success']);

    } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
});