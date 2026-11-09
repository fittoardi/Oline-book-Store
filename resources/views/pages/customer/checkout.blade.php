@extends('layouts.app')

@section('title', 'Checkout — '.config('storefront.brand.name'))

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8" x-data="{ useSavedAddress: {{ $addresses->isNotEmpty() ? 'true' : 'false' }} }">
        <div class="flex items-end justify-between gap-4 border-b border-dotted border-[#faae33] pb-6">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#faae33]">Secure checkout</p>
                <h1 class="font-display mt-2 text-5xl uppercase leading-[0.9] tracking-tight text-[#faae33] sm:text-6xl">Almost Yours.</h1>
            </div>
            <a href="{{ route('cart.index') }}" class="text-xs font-bold uppercase tracking-wider text-[#faae33] hover:underline">Back to cart</a>
        </div>

        @if(session('status'))
            <div class="mt-6 rounded-[6px] border border-[#faae33] bg-[#faae33] px-5 py-3 text-xs font-bold uppercase text-[#281006]">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mt-6 rounded-[6px] border border-[#d1255c] bg-[#281006] p-4 text-xs text-[#faae33]">
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
                <section class="tiger-card p-6">
                    <div class="flex items-center justify-between border-b border-[#6b2e12] pb-4">
                        <h2 class="font-display text-2xl uppercase tracking-tight text-[#faae33]">01. Delivery Address</h2>
                        <span class="tiger-tag tiger-tag-gold !text-[10px]">Required</span>
                    </div>

                    @if($addresses->isNotEmpty())
                        <div class="mt-4 flex gap-4 text-xs font-bold uppercase tracking-wider text-[#faae33]">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="address_mode" value="saved" x-model="useSavedAddress" :value="true" class="accent-[#faae33]">
                                <span>Use saved address</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="address_mode" value="new" x-model="useSavedAddress" :value="false" class="accent-[#faae33]">
                                <span>Enter new address</span>
                            </label>
                        </div>

                        <div x-show="useSavedAddress" class="mt-4 space-y-2">
                            @foreach($addresses as $addr)
                                <label class="flex cursor-pointer items-start gap-3 rounded-[6px] border border-[#6b2e12] p-4 has-[:checked]:border-[#faae33] bg-[#281006]">
                                    <input type="radio" name="address_id" value="{{ $addr->id }}" @checked($loop->first) class="mt-1 accent-[#faae33]">
                                    <div class="text-xs text-[#faae33]">
                                        <div class="flex items-center gap-2">
                                            <strong class="uppercase font-bold">{{ $addr->recipient_name }}</strong>
                                            <span class="tiger-tag !text-[9px]">{{ $addr->label }}</span>
                                        </div>
                                        <p class="mt-1 text-[#9f531b] font-semibold">{{ $addr->phone }}</p>
                                        <p class="mt-1 opacity-85">{{ $addr->full_address }}, {{ $addr->city }} {{ $addr->postal_code }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif

                    <div x-show="!useSavedAddress" class="mt-4 grid gap-4 sm:grid-cols-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#faae33]">
                            Recipient name
                            <input name="name" value="{{ old('name', Auth::user()?->name) }}" class="mt-2 w-full" autocomplete="name">
                        </label>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#faae33]">
                            Phone
                            <input name="phone" value="{{ old('phone', Auth::user()?->phone) }}" class="mt-2 w-full" autocomplete="tel">
                        </label>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#faae33] sm:col-span-2">
                            Full address
                            <textarea name="address" rows="3" class="mt-2 w-full resize-none" autocomplete="street-address" placeholder="Jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota, kode pos">{{ old('address') }}</textarea>
                        </label>
                    </div>
                </section>

                {{-- 02. Shipping Option --}}
                <section class="tiger-card p-6">
                    <h2 class="font-display text-2xl uppercase tracking-tight text-[#faae33] border-b border-[#6b2e12] pb-4">02. Shipping</h2>
                    <label class="mt-4 flex cursor-pointer items-start gap-3 rounded-[6px] border border-[#faae33] p-4 bg-[#281006]">
                        <input type="radio" checked name="shipping_method" value="regular" class="mt-1 accent-[#faae33]">
                        <div class="flex-1 text-xs">
                            <div class="flex items-center justify-between">
                                <strong class="uppercase font-bold text-[#faae33]">Regular delivery</strong>
                                @if($shipping === 0)
                                    <span class="tiger-tag tiger-tag-gold !text-[10px]">FREE</span>
                                @else
                                    <span class="font-bold text-[#faae33]">Rp{{ number_format($shipping, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            <span class="mt-1 block text-[11px] text-[#faae33] opacity-75">
                                {{ config('storefront.shipping.courier') }} · {{ config('storefront.shipping.estimate') }}
                            </span>
                        </div>
                    </label>
                </section>

                {{-- 03. Payment Option --}}
                <section class="tiger-card p-6">
                    <h2 class="font-display text-2xl uppercase tracking-tight text-[#faae33] border-b border-[#6b2e12] pb-4">03. Payment Channel</h2>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        @foreach($paymentChannels as $channel)
                            <label class="flex cursor-pointer items-center gap-3 rounded-[6px] border border-[#6b2e12] p-4 has-[:checked]:border-[#faae33] bg-[#281006]">
                                <input type="radio" name="payment" value="{{ $channel }}" @checked(old('payment', 'Bank transfer') === $channel) class="accent-[#faae33]">
                                <span class="text-xs font-bold uppercase tracking-wider text-[#faae33]">{{ $channel }}</span>
                            </label>
                        @endforeach
                    </div>
                </section>

                {{-- 04. Review Order --}}
                <section class="tiger-card p-6">
                    <h2 class="font-display text-2xl uppercase tracking-tight text-[#faae33] border-b border-[#6b2e12] pb-4">04. Confirm Order</h2>
                    <p class="mt-4 text-xs leading-6 text-[#faae33] opacity-80">
                        Stok buku Anda akan direservasi segera setelah pesanan dibuat. Petunjuk pembayaran akan ditampilkan di halaman detail pesanan.
                    </p>
                    <button class="tiger-button tiger-button-primary mt-6 w-full py-3.5 text-sm" type="submit">
                        Place Order (Rp{{ number_format($total, 0, ',', '.') }}) ↗
                    </button>
                </section>
            </form>

            {{-- Sidebar Order Summary & Voucher --}}
            <aside class="space-y-5 h-fit lg:sticky lg:top-4">
                {{-- Order Summary Card --}}
                <div class="tiger-card p-6">
                    <h2 class="font-display text-2xl uppercase tracking-tight text-[#faae33] border-b border-[#6b2e12] pb-3">Order summary</h2>
                    <div class="mt-4 space-y-3">
                        @foreach($items as $item)
                            @php($product = $item->productVariant->product)
                            <div class="flex gap-3 text-xs">
                                @if($product->primaryImage)
                                    <img src="{{ $product->primaryImage->path }}" alt="{{ $product->name }}" width="48" height="60" class="h-14 w-11 rounded-[3px] border border-[#6b2e12] object-cover">
                                @endif
                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-bold uppercase text-[#faae33]">{{ $product->name }}</p>
                                    <p class="text-[10px] text-[#9f531b]">{{ ucfirst(str_replace('_', ' ', $item->productVariant->format)) }} · {{ $item->quantity }}x</p>
                                    <span class="font-bold text-[#faae33]">Rp{{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 border-t border-dotted border-[#faae33] pt-4 text-xs space-y-2">
                        <div class="flex justify-between text-[#faae33] opacity-85">
                            <span>Subtotal</span>
                            <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-[#faae33] opacity-85">
                            <span>Shipping</span>
                            <span>
                                @if($shipping === 0)
                                    <span class="text-[#faae33] font-bold">FREE</span>
                                @else
                                    Rp{{ number_format($shipping, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                        @if($discount > 0)
                            <div class="flex justify-between text-[#faae33] font-bold">
                                <span>Discount ({{ $voucher?->code }})</span>
                                <span>-Rp{{ number_format($discount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between border-t border-[#6b2e12] pt-3 text-base font-bold text-[#faae33]">
                            <span>Total</span>
                            <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Voucher Card --}}
                <div class="tiger-card p-6">
                    <p class="text-xs font-bold uppercase tracking-widest text-[#9f531b]">Coupon / Voucher</p>
                    @if($voucher)
                        <div class="mt-3 flex items-center justify-between rounded-[6px] border border-[#faae33] bg-[#faae33] p-3 text-xs font-bold text-[#281006]">
                            <div>
                                <span class="block uppercase">{{ $voucher->code }}</span>
                                <span class="text-[10px] opacity-80">{{ $voucher->name }} (-Rp{{ number_format($discount, 0, ',', '.') }})</span>
                            </div>
                            <form method="POST" action="{{ route('checkout.voucher.remove') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="tiger-button tiger-button-ghost !px-2 !py-0.5 !text-[10px] !text-[#281006] !border-[#281006]">Remove</button>
                            </form>
                        </div>
                    @else
                        <form method="POST" action="{{ route('checkout.voucher.apply') }}" class="mt-3 flex gap-2">
                            @csrf
                            <input name="voucher_code" placeholder="Voucher code..." required class="flex-1 !px-3 !py-2 text-xs uppercase">
                            <button type="submit" class="tiger-button tiger-button-primary text-xs">Apply</button>
                        </form>
                        <p class="mt-2 text-[10px] text-[#faae33] opacity-75">Use code <strong>DAILY10</strong> for 10% off (min Rp100k).</p>
                    @endif
                </div>
            </aside>
        </div>
    </section>
@endsection
