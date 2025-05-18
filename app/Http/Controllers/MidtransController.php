<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function handleCallback(Request $request)
    {
        try {
            $notif = new Notification();
            
            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $orderId = $notif->order_id;
            $fraud = $notif->fraud_status;

            // Extract penjualan ID dari order ID (format: ORDER-{id}-{timestamp})
            $penjualanId = explode('-', $orderId)[1];
            
            $penjualan = Penjualan::find($penjualanId);
            
            if (!$penjualan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaction not found'
                ], 404);
            }

            // Handle notification status
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $penjualan->status_order = 'Pending';
                        $penjualan->keterangan_status = 'Payment challenged';
                    } else {
                        $penjualan->status_order = 'Paid';
                        $penjualan->keterangan_status = 'Payment captured';
                    }
                }
            } elseif ($transaction == 'settlement') {
                $penjualan->status_order = 'Paid';
                $penjualan->keterangan_status = 'Payment settled';
            } elseif ($transaction == 'pending') {
                $penjualan->status_order = 'Pending';
                $penjualan->keterangan_status = 'Waiting for payment';
            } elseif ($transaction == 'deny') {
                $penjualan->status_order = 'Failed';
                $penjualan->keterangan_status = 'Payment denied';
            } elseif ($transaction == 'expire') {
                $penjualan->status_order = 'Expired';
                $penjualan->keterangan_status = 'Payment expired';
            } elseif ($transaction == 'cancel') {
                $penjualan->status_order = 'Canceled';
                $penjualan->keterangan_status = 'Payment canceled';
            }

            $penjualan->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Notification handled'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
