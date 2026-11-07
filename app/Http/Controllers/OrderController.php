<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function orders(Request $request)
    {
        $orders = Auth::user()->orders()->with(['items', 'payment', 'shipment'])->latest()->get();

        return view('pages.customer.orders', ['orders' => $orders]);
    }

    public function order(Request $request, string $number)
    {
        $order = $this->findOrder($number);

        return view('pages.customer.order-show', ['order' => $order]);
    }

    public function payment(Request $request, string $number)
    {
        $order = $this->findOrder($number);

        return view('pages.customer.payment', ['order' => $order]);
    }

    public function confirmPayment(Request $request, string $number)
    {
        $order = $this->findOrder($number);

        if ($order->payment) {
            $order->payment->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        }

        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing',
        ]);

        return to_route('orders.show', $order->order_number)->with('status', 'Pembayaran berhasil dikonfirmasi! Pesanan Anda sedang diproses.');
    }

    private function findOrder(string $number): Order
    {
        return Order::query()
            ->with(['items.productVariant.product', 'address', 'payment', 'shipment'])
            ->where('order_number', $number)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }
}
