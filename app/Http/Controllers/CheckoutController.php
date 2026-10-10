<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    private function shippingCost(): int
    {
        return (int) config('storefront.shipping.cost');
    }

    private function freeShippingThreshold(): int
    {
        return (int) config('storefront.shipping.free_threshold');
    }

    /** @var array<int, string> */
    private function paymentChannels(): array
    {
        return config('storefront.payment_channels');
    }

    public function checkout(Request $request)
    {
        $cart = $this->cart($request);

        if (! $cart || $cart->items()->count() === 0) {
            return to_route('cart.index');
        }

        return view('pages.customer.checkout', [
            'cart' => $cart,
            'items' => $cart->items()->with(['productVariant.product.primaryImage'])->get(),
            'subtotal' => $this->subtotal($cart),
            'shipping' => $this->shippingCost(),
            'paymentChannels' => $this->paymentChannels(),
            'addresses' => Auth::check()
                ? Auth::user()->addresses()->orderByDesc('is_default')->get()
                : collect(),
        ]);
    }

    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:500'],
            'payment' => ['required', 'string', 'in:'.implode(',', $this->paymentChannels())],
        ]);

        $cart = $this->cart($request);

        if (! $cart || $cart->items()->count() === 0) {
            return to_route('cart.index');
        }

        $items = $cart->items()->with(['productVariant.product'])->get();

        foreach ($items as $item) {
            if ($item->productVariant->availableStock() < $item->quantity) {
                return back()->withErrors([
                    'address' => 'Stok untuk "'.$item->productVariant->product->name.'" tidak mencukupi.',
                ])->withInput();
            }
        }

        $subtotal = $this->subtotal($cart);
        $total = $subtotal + $this->shippingCost();
        $paymentStatus = $data['payment'] === 'COD' ? 'unpaid' : 'pending';
        $orderStatus = $data['payment'] === 'COD' ? 'processing' : 'pending';

        $order = DB::transaction(function () use ($data, $items, $cart, $subtotal, $total, $paymentStatus, $orderStatus) {
            $defaultAddress = Auth::user()->addresses()->where('is_default', true)->first()
                ?? Auth::user()->addresses()->first();

            $address = Address::create([
                'user_id' => Auth::id(),
                'label' => 'Checkout',
                'recipient_name' => $data['name'],
                'phone' => $data['phone'],
                'province' => $defaultAddress?->province ?? 'DKI Jakarta',
                'city' => $defaultAddress?->city ?? 'Jakarta',
                'district' => $defaultAddress?->district ?? '-',
                'postal_code' => $defaultAddress?->postal_code ?? '00000',
                'full_address' => $data['address'],
                'is_default' => false,
            ]);

            $order = Order::create([
                'user_id' => Auth::id(),
                'address_id' => $address?->id,
                'order_number' => 'KB-'.now()->format('ymdHis').'-'.strtoupper(Str::random(4)),
                'status' => $orderStatus,
                'payment_status' => $paymentStatus,
                'shipping_status' => 'pending',
                'subtotal' => $subtotal,
                'discount_amount' => 0,
                'shipping_cost' => $this->shippingCost(),
                'total_amount' => $total,
                'shipping_courier' => explode(' / ', config('storefront.shipping.courier'))[0],
                'notes' => null,
            ]);

            foreach ($items as $item) {
                $variant = $item->productVariant;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'variant_name' => $variant->format,
                    'sku' => $variant->sku,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->unit_price * $item->quantity,
                ]);

                $variant->decrement('stock', $item->quantity);
            }

            Payment::create([
                'order_id' => $order->id,
                'provider' => 'tripay',
                'reference' => 'T-'.strtoupper(Str::random(10)),
                'merchant_ref' => 'KB-'.$order->id,
                'method' => $data['payment'],
                'status' => $paymentStatus,
                'amount' => $total,
            ]);

            Shipment::create([
                'order_id' => $order->id,
                'courier' => explode(' / ', config('storefront.shipping.courier'))[0],
                'service' => 'REG',
                'shipping_cost' => $this->shippingCost(),
                'estimated_delivery' => now()->addDays(3)->toDateString(),
                'status' => 'pending',
            ]);

            CartItem::query()->where('cart_id', $cart->id)->delete();
            $cart->update(['status' => 'completed']);

            return $order;
        });

        return to_route('orders.index')->with('status', 'Order '.$order->order_number.' has been created.');
    }

    private function cart(Request $request): ?Cart
    {
        return Cart::query()
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->with('items')
            ->first();
    }

    private function subtotal(Cart $cart): int
    {
        return (int) CartItem::query()
            ->where('cart_id', $cart->id)
            ->get()
            ->sum(fn (CartItem $item) => $item->unit_price * $item->quantity);
    }
}
