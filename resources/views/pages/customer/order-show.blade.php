@extends('layouts.app')

@section('title', $order->order_number.' — '.config('storefront.brand.name'))

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-16 sm:px-6 lg:px-8">
        <a href="{{ route('orders.index') }}" class="text-xs font-bold uppercase tracking-wider text-[#faae33] hover:underline">← Back to orders</a>

        @if(session('status'))
            <div class="mt-4 rounded-[6px] border border-[#faae33] bg-[#faae33] px-5 py-3 text-xs font-bold uppercase text-[#281006]">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-6 flex flex-col gap-4 border-b border-dotted border-[#faae33] pb-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#faae33]">Order Detail</p>
                <h1 class="font-display mt-2 text-5xl uppercase leading-[0.9] tracking-tight text-[#faae33] sm:text-6xl">{{ $order->order_number }}</h1>
                <p class="mt-1 text-xs text-[#9f531b]">Placed on {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="tiger-tag !text-[10px]">{{ strtoupper($order->status) }}</span>
                <span class="tiger-tag {{ $order->payment_status === 'paid' ? 'tiger-tag-gold' : 'border-[#faae33] text-[#faae33]' }} !text-[10px]">PAYMENT: {{ strtoupper($order->payment_status) }}</span>
            </div>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_340px]">
            <div class="space-y-6">
                {{-- Items --}}
                <section class="tiger-card p-6">
                    <h2 class="font-display text-2xl uppercase tracking-tight text-[#faae33] border-b border-[#6b2e12] pb-3">Items Purchased</h2>
                    <div class="mt-4 space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex flex-col gap-3 border-b border-[#6b2e12] pb-4 last:border-0 last:pb-0 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex-1">
                                    <h3 class="text-sm font-bold uppercase text-[#faae33]">{{ $item->product_name }}</h3>
                                    <p class="mt-1 text-xs text-[#faae33] opacity-75">
                                        Format: {{ ucfirst(str_replace('_', ' ', (string) $item->variant_name)) }} · SKU: {{ $item->sku }} · {{ $item->quantity }} x Rp{{ number_format($item->unit_price, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm font-bold text-[#faae33]">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    @if($order->status === 'completed' && $item->productVariant?->product)
                                        <a href="{{ route('products.show', $item->productVariant->product->slug) }}#rating" class="tiger-button tiger-button-ghost !px-3 !py-1 text-xs">
                                            Review ↗
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- Delivery Snapshot --}}
                <section class="tiger-card p-6">
                    <h2 class="font-display text-2xl uppercase tracking-tight text-[#faae33] border-b border-[#6b2e12] pb-3">Delivery Information</h2>
                    <div class="mt-4 text-xs leading-6 text-[#faae33]">
                        @if($order->address)
                            <p class="font-bold uppercase text-sm text-[#faae33]">{{ $order->address->recipient_name }}</p>
                            <p class="text-[#9f531b] font-semibold">{{ $order->address->phone }}</p>
                            <p class="mt-2 opacity-85">{{ $order->address->full_address }}</p>
                            <p class="opacity-85">{{ $order->address->city }}, {{ $order->address->postal_code }}</p>
                        @else
                            <p class="opacity-75">Address snapshot not available.</p>
                        @endif

                        <div class="mt-4 border-t border-[#6b2e12] pt-3 opacity-85 space-y-1">
                            <p>Courier: <strong>{{ $order->shipping_courier ?? config('storefront.shipping.courier') }}</strong></p>
                            <p>Shipping Status: <span class="uppercase font-bold text-[#faae33]">{{ $order->shipping_status }}</span></p>
                            @if($order->tracking_number)
                                <p class="mt-1">Tracking Number: <code class="rounded-[3px] border border-[#6b2e12] bg-[#281006] px-2 py-0.5 font-bold text-[#faae33]">{{ $order->tracking_number }}</code></p>
                            @endif
                        </div>
                    </div>
                </section>
            </div>

            {{-- Summary Sidebar --}}
            <aside class="tiger-card h-fit p-6">
                <h2 class="font-display text-2xl uppercase tracking-tight text-[#faae33] border-b border-[#6b2e12] pb-3">Summary</h2>
                <div class="mt-4 space-y-2 border-b border-[#6b2e12] pb-4 text-xs">
                    <div class="flex justify-between text-[#faae33] opacity-85">
                        <span>Subtotal</span>
                        <span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-[#faae33] opacity-85">
                        <span>Shipping</span>
                        <span>
                            @if($order->shipping_cost == 0)
                                <span class="text-[#faae33] font-bold">FREE</span>
                            @else
                                Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}
                            @endif
                        </span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-[#faae33] font-bold">
                            <span>Discount</span>
                            <span>-Rp{{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>

                <div class="mt-4 flex justify-between text-base font-bold text-[#faae33]">
                    <span>Total Amount</span>
                    <span>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>

                <div class="mt-5 border-t border-[#6b2e12] pt-4 text-xs space-y-1 text-[#faae33] opacity-85">
                    <p>Payment: <strong class="text-[#faae33] uppercase">{{ $order->payment?->method }}</strong></p>
                    <p>Payment Status: <strong class="text-[#faae33] uppercase">{{ $order->payment?->status }}</strong></p>
                    @if($order->notes)
                        <p class="mt-2 text-[11px] text-[#9f531b]">Note: {{ $order->notes }}</p>
                    @endif
                </div>

                @if($order->payment && $order->payment->status !== 'paid')
                    <a href="{{ route('payments.show', $order->order_number) }}" class="tiger-button tiger-button-primary mt-6 w-full text-center py-3">
                        Complete payment ↗
                    </a>
                @endif

                <a href="{{ route('chat.index') }}?order={{ $order->order_number }}" class="tiger-button tiger-button-ghost mt-3 w-full text-center text-xs">
                    Ask about this order
                </a>

                @if($order->status === 'pending' && $order->payment_status !== 'paid')
                    <form method="POST" action="{{ route('orders.cancel', $order->order_number) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');" class="mt-3">
                        @csrf
                        <button type="submit" class="tiger-button-ghost w-full text-center text-xs !text-[#d1255c] !border-[#d1255c] hover:!bg-[#d1255c] hover:!text-white transition-colors">
                            Cancel order
                        </button>
                    </form>
                @endif
            </aside>
        </div>
    </section>
@endsection
