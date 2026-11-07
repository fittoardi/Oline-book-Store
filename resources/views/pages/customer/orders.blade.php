@extends('layouts.app')

@section('title', 'Order History — '.config('storefront.brand.name'))

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="border-b border-black pb-6">
            <p class="text-xs font-bold uppercase tracking-[.2em] text-[#faa21f]">Your account</p>
            <h1 class="mt-3 text-4xl font-bold uppercase">Order history</h1>
        </div>

        @if(session('status'))
            <div class="mt-6 border border-black bg-[#faa21f] px-4 py-3 text-sm font-bold text-black">
                {{ session('status') }}
            </div>
        @endif

        @if($orders->isEmpty())
            <div class="arcade-card mt-8 py-16 text-center">
                <p class="text-lg font-bold uppercase">No orders yet.</p>
                <p class="mt-2 text-sm text-[#737373]">Your next favourite book is waiting on the shelf.</p>
                <a href="{{ route('products.index') }}" class="arcade-button arcade-button-primary mt-6 inline-flex uppercase">Start shopping ↗</a>
            </div>
        @else
            <div class="mt-8 space-y-4">
                @foreach($orders as $order)
                    <article class="arcade-card">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between border-b border-[#e5e7eb] pb-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <strong class="text-sm uppercase font-bold">{{ $order->order_number }}</strong>
                                    <span class="arcade-tag bg-[#f3e5df] text-[10px]">{{ strtoupper($order->status) }}</span>
                                    <span class="arcade-tag {{ $order->payment_status === 'paid' ? 'bg-[#128e44] text-white' : 'bg-[#faa21f]' }} text-[10px]">
                                        {{ strtoupper($order->payment_status) }}
                                    </span>
                                </div>
                                <p class="mt-1 text-xs text-[#737373]">Placed on {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                            </div>
                            <div class="text-left sm:text-right">
                                <span class="text-xs text-[#737373] uppercase">Total</span>
                                <p class="text-lg font-bold">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="mt-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div class="text-xs text-[#737373]">
                                <span class="font-bold text-black">{{ $order->items->count() }} item(s)</span>
                                @if($order->items->isNotEmpty())
                                    : {{ $order->items->pluck('product_name')->take(2)->implode(', ') }}@if($order->items->count() > 2)...@endif
                                @endif
                                <span class="block mt-0.5">Payment: {{ $order->payment?->method }}</span>
                            </div>

                            <div class="flex items-center gap-2">
                                @if($order->payment && $order->payment->status !== 'paid')
                                    <a href="{{ route('payments.show', $order->order_number) }}" class="arcade-button arcade-button-primary text-xs uppercase font-bold">
                                        Pay now ↗
                                    </a>
                                @endif
                                <a href="{{ route('orders.show', $order->order_number) }}" class="arcade-button arcade-button-ghost text-xs uppercase">
                                    View details ↗
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
