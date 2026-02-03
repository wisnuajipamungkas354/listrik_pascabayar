<?php

use App\Http\Controllers\TagihanController;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Midtrans\Config as MidtransConfig;
use Midtrans\Notification;

Route::post('/midtrans-callback', function(Request $request) {
      MidtransConfig::$serverKey = config('services.midtrans.server_key');
      MidtransConfig::$isProduction = config('services.midtrans.is_production');

      try {
          $notif = new Notification();
          $transaction = $notif->transaction_status;
          $order_id = $notif->order_id; // Format: TRX-{id}-{time}
          
          // Ambil ID tagihan asli dari order_id
          $tagihanId = explode('-', $order_id)[1];
          $tagihan = Tagihan::find($tagihanId);

          Pembayaran::create([
            'tagihan_id' => $tagihanId,
            'tanggal_pembayaran' => date('Y-m-d'),
            'biaya_admin' => 2500,
            'total_bayar' => $notif->gross_amount,
            'metode_pembayaran' => 'TRANSFER',
          ]);

          if ($transaction == 'settlement') {
              $tagihan->update(['status' => 'LUNAS']);
          } elseif ($transaction == 'pending') {
              $tagihan->update(['status' => 'BELUM_BAYAR']);
          }

          return response()->json(['message' => 'OK']);
      } catch (\Exception $e) {
          return response()->json(['message' => $e->getMessage()], 500);
      }
});