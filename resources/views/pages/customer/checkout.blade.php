@extends('layouts.app')

@section('title', 'Checkout — '.config('storefront.brand.name'))

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8" x-data="{ useSavedAddress: {{ $addresses->isNotEmpty() ? 'true' : 'false' }} }">
        <div class="flex items-end justify-between gap-4 border-b border-black pb-6">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.2em] text-[#faa21f]">Secure checkout</p>
                <h1 class="mt-3 text-4xl font-bold uppercase">Almost yours.</h1>
            </div>
            <a href="{{ route('cart.index') }}" class="text-sm font-bold uppercase hover:underline">Back to cart</a>
        </div>

        @if(session('status'))
            <div class="mt-6 border border-black bg-[#faa21f] px-4 py-3 text-sm font-bold text-black">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mt-6 border border-black bg-red-100 p-4 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_340px]">
            {{-- Main Form --}}
            <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form" class="space-y-6">
                @csrf

                {{-- 01. Delivery Address --}}
                <section class="arcade-card">
                    <div class="flex items-center justify-between border-b border-[#e5e7eb] pb-4">
                        <h2 class="text-lg font-bold uppercase">01. Delivery address</h2>
                        <span class="arcade-tag">Required</span>
                    </div>

                    @if($addresses->isNotEmpty())
                        <div class="mt-4 flex gap-4 text-xs font-bold uppercase">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="address_mode" value="saved" x-model="useSavedAddress" :value="true">
                                <span>Use saved address</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="address_mode" value="new" x-model="useSavedAddress" :value="false">
                                <span>Enter new address</span>
                            </label>
                        </div>

                        <div x-show="useSavedAddress" class="mt-4 space-y-2">
                            @foreach($addresses as $addr)
                                <label class="flex cursor-pointer items-start gap-3 border border-[#e5e7eb] p-3 has-[:checked]:border-black bg-[#f3e5df]">
                                    <input type="radio" name="address_id" value="{{ $addr->id }}" @checked($loop->first) class="mt-1">
                                    <div class="text-xs">
                                        <div class="flex items-center gap-2">
                                            <strong class="uppercase">{{ $addr->recipient_name }}</strong>
                                            <span class="arcade-tag text-[9px]">{{ $addr->label }}</span>
                                        </div>
                                        <p class="mt-1 text-[#737373]">{{ $addr->phone }}</p>
                                        <p class="mt-1 text-[#333333]">{{ $addr->full_address }}, {{ $addr->city }} {{ $addr->postal_code }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif

                    <div x-show="!useSavedAddress" class="mt-4 grid gap-4 sm:grid-cols-2">
                        <label class="text-sm">
                            Recipient name
                            <input name="name" value="{{ old('name', Auth::user()?->name) }}" class="mt-2 w-full border border-black bg-[#f3e5df] px-3 py-3 text-sm" autocomplete="name">
                        </label>
                        <label class="text-sm">
                            Phone
                            <input name="phone" value="{{ old('phone', Auth::user()?->phone) }}" class="mt-2 w-full border border-black bg-[#f3e5df] px-3 py-3 text-sm" autocomplete="tel">
                        </label>
                        <label class="text-sm sm:col-span-2">
                            Full address
                            <textarea name="address" rows="3" class="mt-2 w-full resize-none border border-black bg-[#f3e5df] px-3 py-3 text-sm" autocomplete="street-address" placeholder="Jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota, kode pos">{{ old('address') }}</textarea>
                        </label>
                    </div>
                </section>

                {{-- 02. Shipping Option --}}
                <section class="arcade-card">
                    <h2 class="text-lg font-bold uppercase border-b border-[#e5e7eb] pb-4">02. Shipping</h2>
                    <label class="mt-4 flex cursor-pointer items-start gap-3 border border-black p-4 bg-[#f3e5df]">
                        <input type="radio" checked name="shipping_method" value="regular" class="mt-1">
                        <div class="flex-1 text-sm">
                            <div class="flex items-center justify-between">
                                <strong class="uppercase">Regular delivery</strong>
                                @if($shipping === 0)
                                    <span class="arcade-tag bg-[#128e44] text-white">FREE</span>
                                @else
                                    <span class="font-bold">Rp{{ number_format($shipping, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            <span class="mt-1 block text-xs text-[#737373]">
                                {{ config('storefront.shipping.courier') }} · {{ config('storefront.shipping.estimate') }}
                            </span>
                        </div>
                    </label>
                </section>

                {{-- 03. Payment Option --}}
                <section class="arcade-card">
                    <h2 class="text-lg font-bold uppercase border-b border-[#e5e7eb] pb-4">03. Payment Channel</h2>
                    <div class="mt-4 grid gap-2 sm:grid-cols-2">
                        @foreach($paymentChannels as $channel)
                            <label class="flex cursor-pointer items-center gap-3 border border-[#e5e7eb] p-4 has-[:checked]:border-black bg-[#f3e5df]">
                                <input type="radio" name="payment" value="{{ $channel }}" @checked(old('payment', 'Bank transfer') === $channel)>
                                <span class="text-sm font-bold uppercase">{{ $channel }}</span>
                            </label>
                        @endforeach
                    </div>
                </section>

                {{-- 04. Review Order --}}
                <section class="arcade-card">
                    <h2 class="text-lg font-bold uppercase border-b border-[#e5e7eb] pb-4">04. Confirm Order</h2>
                    <p class="mt-4 text-xs leading-6 text-[#737373]">
                        Stok buku Anda akan direservasi segera setelah pesanan dibuat. Petunjuk pembayaran akan ditampilkan di halaman detail pesanan.
                    </p>
                    <button class="arcade-button arcade-button-primary mt-6 w-full uppercase font-bold py-3 text-base" type="submit">
                        Place Order (Rp{{ number_format($total, 0, ',', '.') }}) ↗
                    </button>
                </section>
            </form>

            {{-- Sidebar Order Summary & Voucher --}}
            <aside class="space-y-5 h-fit lg:sticky lg:top-4">
                {{-- Order Summary Card --}}
                <div class="arcade-card">
                    <h2 class="text-lg font-bold uppercase border-b border-[#e5e7eb] pb-3">Order summary</h2>
                    <div class="mt-4 space-y-3">
                        @foreach($items as $item)
                            @php($product = $item->productVariant->product)
                            <div class="flex gap-3 text-sm">
                                @if($product->primaryImage)
                                    <img src="{{ $product->primaryImage->path }}" alt="{{ $product->name }}" width="48" height="60" class="h-14 w-11 object-cover border border-[#e5e7eb]">
                                @endif
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-xs font-bold uppercase">{{ $product->name }}</p>
                                    <p class="text-[11px] text-[#737373]">{{ ucfirst(str_replace('_', ' ', $item->productVariant->format)) }} · {{ $item->quantity }}x</p>
                                    <span class="text-xs font-bold">Rp{{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 border-t border-black pt-4 text-xs space-y-2">
                        <div class="flex justify-between text-[#737373]">
                            <span>Subtotal</span>
                            <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-[#737373]">
                            <span>Shipping</span>
                            <span>
                                @if($shipping === 0)
                                    <span class="text-[#128e44] font-bold">FREE</span>
                                @else
                                    Rp{{ number_format($shipping, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                        @if($discount > 0)
                            <div class="flex justify-between text-[#128e44] font-bold">
                                <span>Discount ({{ $voucher?->code }})</span>
                                <span>-Rp{{ number_format($discount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between border-t border-black pt-3 text-base font-bold text-black">
                            <span>Total</span>
                            <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Voucher Card --}}
                <div class="arcade-card">
                    <p class="text-xs font-bold uppercase tracking-[.15em] text-[#faa21f]">Coupon / Voucher</p>
                    @if($voucher)
                        <div class="mt-3 flex items-center justify-between border border-black bg-[#faa21f] p-3 text-xs font-bold">
                            <div>
                                <span class="block uppercase">{{ $voucher->code }}</span>
                                <span class="text-[10px] text-[#333]">{{ $voucher->name }} (-Rp{{ number_format($discount, 0, ',', '.') }})</span>
                            </div>
                            <form method="POST" action="{{ route('checkout.voucher.remove') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="arcade-button arcade-button-ghost text-[10px] px-2 py-1">Remove</button>
                            </form>
                        </div>
                    @else
                        <form method="POST" action="{{ route('checkout.voucher.apply') }}" class="mt-3 flex gap-2">
                            @csrf
                            <input name="voucher_code" placeholder="Voucher code..." required class="flex-1 border border-black bg-[#f3e5df] px-3 py-2 text-xs uppercase">
                            <button type="submit" class="arcade-button arcade-button-primary uppercase text-xs">Apply</button>
                        </form>
                        <p class="mt-2 text-[10px] text-[#737373]">Use code <strong>DAILY10</strong> for 10% off (min Rp100k).</p>
                    @endif
                </div>
            </aside>
        </div>
    </section>
@endsection
