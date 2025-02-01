<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createTransaction(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'first_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'items' => 'required|array',
        ]);

        $items = $request->input('items');

        $params = [
            'transaction_details' => [
                'order_id' => uniqid(), // Generate unique order_id
                'gross_amount' => $request->input('amount'),
            ],
            'customer_details' => [
                'first_name' => $request->input('first_name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'billing_address' => [
                    'address' => $request->input('address'),
                ],
                'shipping_address' => [
                    'first_name' => $request->input('first_name'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'address' => $request->input('address'),
                ],
            ],
            'item_details' => $items,
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json([
                'token' => $snapToken,
                'order_id' => $params['transaction_details']['order_id']
            ])->header('ngrok-skip-browser-warning', 'true');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function handleFinish(Request $request)
{
    \Log::info('Payment Finished: ', $request->all());

    $orderId = $request->input('order_id');
    \App\Models\Order::where('order_id', $orderId)->update(['status' => 'completed']);

    // Redirect ke aplikasi Flutter menggunakan deep link
    return redirect('yourapp://finish');
}

public function handleUnfinish(Request $request)
{
    \Log::info('Payment Unfinished: ', $request->all());

    $orderId = $request->input('order_id');
    \App\Models\Order::where('order_id', $orderId)->update(['status' => 'pending']);

    return redirect('yourapp://unfinish');
}

public function handleError(Request $request)
{
    \Log::info('Payment Error: ', $request->all());

    $orderId = $request->input('order_id');
    \App\Models\Order::where('order_id', $orderId)->update(['status' => 'failed']);

    return redirect('yourapp://error');
}


}
