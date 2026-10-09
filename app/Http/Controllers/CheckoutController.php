<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $cart = $this->cartItems($request);

        if ($cart === []) {
            return to_route('cart.index');
        }

        return view('pages.customer.checkout', [
            'cart' => $cart,
            'subtotal' => collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']),
            'shipping' => 15000,
            'paymentChannels' => ['Bank transfer', 'E-wallet', 'QRIS', 'COD'],
        ]);
    }

    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:500'],
            'payment' => ['required', 'string', 'in:Bank transfer,E-wallet,QRIS,COD'],
        ]);

        $cart = $this->cartItems($request);

        if ($cart === []) {
            return to_route('cart.index');
        }

        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $order = [
            'number' => 'KH-'.now()->format('ymdHis'),
            'items' => $cart,
            'subtotal' => $subtotal,
            'shipping' => 15000,
            'total' => $subtotal + 15000,
            'payment' => $data['payment'],
            'customer' => $data,
            'status' => $data['payment'] === 'COD' ? 'Processing' : 'Awaiting payment',
        ];

        $orders = $request->session()->get('orders', []);
        array_unshift($orders, $order);
        $request->session()->put(['orders' => $orders, 'cart' => []]);

        return to_route('orders.index')->with('status', 'Order '.$order['number'].' has been created.');
    }

    private function cartItems(Request $request): array
    {
        return array_values($request->session()->get('cart', []));
    }
}
