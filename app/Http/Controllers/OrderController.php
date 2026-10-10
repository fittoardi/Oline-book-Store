<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function orders(Request $request)
    {
        $orders = Auth::user()->orders()->with('items')->latest()->get();

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

    private function findOrder(string $number): Order
    {
        return Order::query()
            ->with(['items.productVariant.product', 'address', 'payment', 'shipment'])
            ->where('order_number', $number)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }
}
