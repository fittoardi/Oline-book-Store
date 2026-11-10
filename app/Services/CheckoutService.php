<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
    public function __construct(public CartService $cartService) {}

    public function shippingCost(): int
    {
        return (int) config('storefront.shipping.cost', 15000);
    }

    public function freeShippingThreshold(): int
    {
        return (int) config('storefront.shipping.free_threshold', 300000);
    }

    /**
     * @return array<int, string>
     */
    public function paymentChannels(): array
    {
        return config('storefront.payment_channels', ['Bank transfer', 'E-wallet', 'QRIS', 'COD']);
    }

    /**
     * Calculate summary details for checkout.
     *
     * @return array{subtotal: int, shipping: int, discount: int, total: int, voucher: ?Voucher}
     */
    public function calculate(Cart $cart, ?string $voucherCode = null): array
    {
        $subtotal = $this->cartService->subtotal($cart);
        $shipping = $subtotal >= $this->freeShippingThreshold() ? 0 : $this->shippingCost();

        $voucher = null;
        $discount = 0;

        if ($voucherCode) {
            $voucher = Voucher::where('code', strtoupper(trim($voucherCode)))->first();
            if ($voucher && $voucher->isValid($subtotal)) {
                $discount = (int) $voucher->calculateDiscount($subtotal);
            } else {
                $voucher = null;
            }
        }

        $total = max(0, $subtotal - $discount + $shipping);

        return [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount' => $discount,
            'total' => $total,
            'voucher' => $voucher,
        ];
    }

    /**
     * Place order in a database transaction with snapshots and stock decrement.
     */
    public function placeOrder(User $user, Cart $cart, array $data, ?string $voucherCode = null): Order
    {
        return DB::transaction(function () use ($user, $cart, $data, $voucherCode) {
            $items = $cart->items()->with(['productVariant.product'])->lockForUpdate()->get();

            if ($items->isEmpty()) {
                throw new \RuntimeException('Keranjang belanja kosong.');
            }

            foreach ($items as $item) {
                if ($item->productVariant->availableStock() < $item->quantity) {
                    throw new \RuntimeException('Stok untuk "'.$item->productVariant->product->name.'" tidak mencukupi.');
                }
            }

            $calculation = $this->calculate($cart, $voucherCode);
            $subtotal = $calculation['subtotal'];
            $shipping = $calculation['shipping'];
            $discount = $calculation['discount'];
            $total = $calculation['total'];
            $voucher = $calculation['voucher'];

            $paymentStatus = ($data['payment'] ?? '') === 'COD' ? 'unpaid' : 'pending';
            $orderStatus = ($data['payment'] ?? '') === 'COD' ? 'processing' : 'pending';

            $address = null;
            if (! empty($data['address_id'])) {
                $address = $user->addresses()->find($data['address_id']);
            }

            if (! $address) {
                $defaultAddress = $user->addresses()->where('is_default', true)->first()
                    ?? $user->addresses()->first();

                $address = Address::create([
                    'user_id' => $user->id,
                    'label' => 'Checkout',
                    'recipient_name' => $data['name'] ?? $user->name,
                    'phone' => $data['phone'] ?? $user->phone ?? '08123456789',
                    'province' => $defaultAddress?->province ?? 'DKI Jakarta',
                    'city' => $defaultAddress?->city ?? 'Jakarta',
                    'district' => $defaultAddress?->district ?? '-',
                    'postal_code' => $defaultAddress?->postal_code ?? '10110',
                    'full_address' => $data['address'] ?? 'Alamat pengiriman',
                    'is_default' => false,
                ]);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $address?->id,
                'order_number' => 'KB-'.now()->format('ymdHis').'-'.strtoupper(Str::random(4)),
                'status' => $orderStatus,
                'payment_status' => $paymentStatus,
                'shipping_status' => 'pending',
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'shipping_cost' => $shipping,
                'total_amount' => $total,
                'shipping_courier' => explode(' / ', config('storefront.shipping.courier', 'JNE / J&T'))[0],
                'notes' => $voucher ? 'Voucher: '.$voucher->code : null,
            ]);

            if ($voucher) {
                $voucher->increment('used_count');
            }

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
                'method' => $data['payment'] ?? 'Bank transfer',
                'status' => $paymentStatus,
                'amount' => $total,
            ]);

            Shipment::create([
                'order_id' => $order->id,
                'courier' => explode(' / ', config('storefront.shipping.courier', 'JNE / J&T'))[0],
                'service' => 'REG',
                'shipping_cost' => $shipping,
                'estimated_delivery' => now()->addDays(3)->toDateString(),
                'status' => 'pending',
            ]);

            CartItem::query()->where('cart_id', $cart->id)->delete();
            $cart->update(['status' => 'completed']);

            return $order;
        });
    }
}
