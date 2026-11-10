<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        DB::transaction(function () use ($order) {
            if ($order->payment) {
                $order->payment->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                PaymentWebhook::create([
                    'payment_id' => $order->payment->id,
                    'reference' => $order->payment->reference ?? 'REF-'.$order->id,
                    'event' => 'payment.confirmed',
                    'payload' => [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'amount' => $order->total_amount,
                        'status' => 'paid',
                    ],
                    'signature' => hash_hmac('sha256', $order->order_number, config('app.key') ?: 'daily-books-key'),
                    'processed_at' => now(),
                ]);
            }

            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
            ]);
        });

        return to_route('orders.show', $order->order_number)->with('status', 'Pembayaran berhasil dikonfirmasi! Pesanan Anda sedang diproses.');
    }

    public function cancel(Request $request, string $number)
    {
        $order = $this->findOrder($number);

        if ($order->status !== 'pending' || $order->payment_status === 'paid') {
            return back()->withErrors(['cancel' => 'Pesanan tidak dapat dibatalkan karena sudah diproses atau dibayar.']);
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'cancelled']);

            // Restore variant stock
            foreach ($order->items as $item) {
                if ($item->productVariant) {
                    $item->productVariant->increment('stock', $item->quantity);
                }
            }

            if ($order->payment) {
                $order->payment->update(['status' => 'expired']);
            }
        });

        return back()->with('status', 'Pesanan '.$order->order_number.' berhasil dibatalkan.');
    }

    public function webhook(Request $request)
    {
        $data = $request->validate([
            'order_number' => ['required', 'string'],
            'status' => ['required', 'string', 'in:paid,failed,expired'],
            'reference' => ['nullable', 'string'],
        ]);

        $order = Order::where('order_number', $data['order_number'])->firstOrFail();

        DB::transaction(function () use ($order, $data) {
            if ($data['status'] === 'paid' && $order->payment_status !== 'paid') {
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
            } elseif (in_array($data['status'], ['failed', 'expired'])) {
                if ($order->payment) {
                    $order->payment->update(['status' => $data['status']]);
                }
                $order->update(['payment_status' => $data['status']]);
            }

            PaymentWebhook::create([
                'payment_id' => $order->payment?->id,
                'reference' => $data['reference'] ?? $order->payment?->reference ?? 'WEBHOOK-'.$order->id,
                'event' => 'payment.'.$data['status'],
                'payload' => $data,
                'signature' => hash_hmac('sha256', $order->order_number, config('app.key') ?: 'daily-books-key'),
                'processed_at' => now(),
            ]);
        });

        return response()->json(['success' => true]);
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
