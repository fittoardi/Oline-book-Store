<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function orders(Request $request)
    {
        return view('pages.customer.orders', [
            'orders' => $request->session()->get('orders', []),
        ]);
    }

    public function order(Request $request, string $number)
    {
        $order = collect($request->session()->get('orders', []))->firstWhere('number', $number);

        abort_unless($order, 404);

        return view('pages.customer.order-show', ['order' => $order]);
    }

    public function payment(Request $request, string $number)
    {
        $order = collect($request->session()->get('orders', []))->firstWhere('number', $number);

        abort_unless($order, 404);

        return view('pages.customer.payment', ['order' => $order]);
    }
}
