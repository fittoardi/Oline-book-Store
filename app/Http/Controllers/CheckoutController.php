<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Voucher;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct(
        public CheckoutService $checkoutService,
        public CartService $cartService,
    ) {}

    public function checkout(Request $request)
    {
        $cart = $this->cart($request);

        if (! $cart || $cart->items()->count() === 0) {
            return to_route('cart.index');
        }

        $voucherCode = $request->session()->get('checkout_voucher');
        $calc = $this->checkoutService->calculate($cart, $voucherCode);

        if (! $calc['voucher'] && $voucherCode) {
            $request->session()->forget('checkout_voucher');
        }

        return view('pages.customer.checkout', [
            'cart' => $cart,
            'items' => $cart->items()->with(['productVariant.product.primaryImage'])->get(),
            'subtotal' => $calc['subtotal'],
            'shipping' => $calc['shipping'],
            'voucher' => $calc['voucher'],
            'discount' => $calc['discount'],
            'total' => $calc['total'],
            'freeShippingThreshold' => $this->checkoutService->freeShippingThreshold(),
            'paymentChannels' => $this->checkoutService->paymentChannels(),
            'addresses' => Auth::check()
                ? Auth::user()->addresses()->orderByDesc('is_default')->get()
                : collect(),
        ]);
    }

    public function applyVoucher(Request $request)
    {
        $data = $request->validate([
            'voucher_code' => ['required', 'string', 'max:50'],
        ]);

        $cart = $this->cart($request);
        if (! $cart || $cart->items()->count() === 0) {
            return to_route('cart.index');
        }

        $subtotal = $this->cartService->subtotal($cart);
        $code = strtoupper(trim($data['voucher_code']));
        $voucher = Voucher::where('code', $code)->first();

        if (! $voucher) {
            return back()->withErrors(['voucher' => 'Kode voucher "'.$code.'" tidak ditemukan.'])->withInput();
        }

        if ($reason = $voucher->invalidationReason($subtotal)) {
            return back()->withErrors(['voucher' => $reason])->withInput();
        }

        $request->session()->put('checkout_voucher', $voucher->code);

        return back()->with('status', 'Voucher '.$voucher->code.' berhasil diterapkan!');
    }

    public function removeVoucher(Request $request)
    {
        $request->session()->forget('checkout_voucher');

        return back()->with('status', 'Voucher dilepas.');
    }

    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'address_id' => ['nullable', 'integer', 'exists:addresses,id'],
            'name' => ['required_without:address_id', 'nullable', 'string', 'max:100'],
            'phone' => ['required_without:address_id', 'nullable', 'string', 'max:30'],
            'address' => ['required_without:address_id', 'nullable', 'string', 'max:500'],
            'payment' => ['required', 'string', 'in:'.implode(',', $this->checkoutService->paymentChannels())],
            'voucher_code' => ['nullable', 'string', 'max:50'],
        ]);

        $cart = $this->cart($request);

        if (! $cart || $cart->items()->count() === 0) {
            return to_route('cart.index');
        }

        $code = $data['voucher_code'] ?? $request->session()->get('checkout_voucher');

        try {
            $order = $this->checkoutService->placeOrder(Auth::user(), $cart, $data, $code);
        } catch (\RuntimeException $e) {
            return back()->withErrors(['address' => $e->getMessage()])->withInput();
        }

        $request->session()->forget('checkout_voucher');

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
}
