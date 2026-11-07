@extends('layouts.app')

@section('title', $order->order_number.' — '.config('storefront.brand.name'))

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <a href="{{ route('orders.index') }}" class="text-sm font-bold uppercase hover:underline">← Back to orders</a>

        @if(session('status'))
            <div class="mt-4 border border-black bg-[#faa21f] px-4 py-3 text-sm font-bold text-black">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-6 flex flex-col gap-4 border-b border-black pb-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.2em] text-[#faa21f]">Order detail</p>
                <h1 class="mt-3 text-4xl font-bold uppercase">{{ $order->order_number }}</h1>
                <p class="mt-1 text-xs text-[#737373]">Placed on {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="arcade-tag bg-[#f3e5df]">{{ strtoupper($order->status) }}</span>
                <span class="arcade-tag {{ $order->payment_status === 'paid' ? 'bg-[#128e44] text-white' : 'bg-[#faa21f]' }}">PAYMENT: {{ strtoupper($order->payment_status) }}</span>
            </div>
        </div>

        <div class="mt-8 grid gap-5 lg:grid-cols-[1fr_320px]">
            <div class="space-y-5">
                {{-- Items --}}
                <section class="arcade-card">
                    <h2 class="text-lg font-bold uppercase border-b border-[#e5e7eb] pb-3">Items Purchased</h2>
                    <div class="mt-4 space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex flex-col gap-3 border-b border-[#e5e7eb] pb-4 last:border-0 last:pb-0 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex-1">
                                    <h3 class="text-sm font-bold uppercase">{{ $item->product_name }}</h3>
                                    <p class="mt-1 text-xs text-[#737373]">
                                        Format: {{ ucfirst(str_replace('_', ' ', (string) $item->variant_name)) }} · SKU: {{ $item->sku }} · {{ $item->quantity }} x Rp{{ number_format($item->unit_price, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm font-bold">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    @if($order->status === 'completed' && $item->productVariant?->product)
                                        <a href="{{ route('products.show', $item->productVariant->product->slug) }}#rating" class="arcade-button arcade-button-ghost text-xs uppercase">
                                            Review ↗
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- Delivery Snapshot --}}
                <section class="arcade-card">
                    <h2 class="text-lg font-bold uppercase border-b border-[#e5e7eb] pb-3">Delivery Information</h2>
                    <div class="mt-4 text-xs leading-6 text-[#333333]">
                        @if($order->address)
                            <p class="font-bold uppercase text-sm text-black">{{ $order->address->recipient_name }}</p>
                            <p class="text-[#737373]">{{ $order->address->phone }}</p>
                            <p class="mt-2">{{ $order->address->full_address }}</p>
                            <p>{{ $order->address->city }}, {{ $order->address->postal_code }}</p>
                        @else
                            <p class="text-[#737373]">Address snapshot not available.</p>
                        @endif

                        <div class="mt-4 border-t border-[#e5e7eb] pt-3 text-[#737373]">
                            <p>Courier: <strong>{{ $order->shipping_courier ?? config('storefront.shipping.courier') }}</strong></p>
                            <p>Shipping Status: <span class="uppercase font-bold text-black">{{ $order->shipping_status }}</span></p>
                            @if($order->tracking_number)
                                <p class="mt-1">Tracking Number: <code class="border border-black px-2 py-1 font-bold text-black">{{ $order->tracking_number }}</code></p>
                            @endif
                        </div>
                    </div>
                </section>
            </div>

            {{-- Summary Sidebar --}}
            <aside class="arcade-card h-fit">
                <h2 class="text-lg font-bold uppercase border-b border-[#e5e7eb] pb-3">Summary</h2>
                <div class="mt-4 space-y-2 border-b border-black pb-4 text-xs">
                    <div class="flex justify-between text-[#737373]">
                        <span>Subtotal</span>
                        <span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-[#737373]">
                        <span>Shipping</span>
                        <span>
                            @if($order->shipping_cost == 0)
                                <span class="text-[#128e44] font-bold">FREE</span>
                            @else
                                Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}
                            @endif
                        </span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-[#128e44] font-bold">
                            <span>Discount</span>
                            <span>-Rp{{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>

                <div class="mt-4 flex justify-between text-base font-bold">
                    <span>Total Amount</span>
                    <span>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>

                <div class="mt-5 border-t border-[#e5e7eb] pt-4 text-xs space-y-1 text-[#737373]">
                    <p>Payment: <strong class="text-black uppercase">{{ $order->payment?->method }}</strong></p>
                    <p>Payment Status: <strong class="text-black uppercase">{{ $order->payment?->status }}</strong></p>
                    @if($order->notes)
                        <p class="mt-2 text-[11px] text-[#333]">Note: {{ $order->notes }}</p>
                    @endif
                </div>

                @if($order->payment && $order->payment->status !== 'paid')
                    <a href="{{ route('payments.show', $order->order_number) }}" class="arcade-button arcade-button-primary mt-5 flex justify-center uppercase font-bold py-3 text-center">
                        Complete payment ↗
                    </a>
                @endif

                <a href="{{ route('chat.index') }}?order={{ $order->order_number }}" class="arcade-button arcade-button-ghost mt-3 flex justify-center uppercase text-xs py-2 text-center">
                    Ask about this order
                </a>
            </aside>
        </div>
    </section>
@endsection
