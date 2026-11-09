@extends('layouts.app')

@section('title', 'Order History — '.config('storefront.brand.name'))

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="border-b border-dotted border-[#faae33] pb-6">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#faae33]">Your Account</p>
            <h1 class="font-display mt-2 text-5xl uppercase leading-[0.9] tracking-tight text-[#faae33] sm:text-6xl">Order History</h1>
        </div>

        @if(session('status'))
            <div class="mt-6 rounded-[6px] border border-[#faae33] bg-[#faae33] px-5 py-3 text-xs font-bold uppercase text-[#281006]">
                {{ session('status') }}
            </div>
        @endif

        @if($orders->isEmpty())
            <div class="tiger-card mt-8 py-20 text-center">
                <p class="font-display text-4xl uppercase text-[#faae33]">No orders yet.</p>
                <p class="mt-2 text-xs text-[#faae33] opacity-75">Your next favourite book is waiting on the shelf.</p>
                <a href="{{ route('products.index') }}" class="tiger-button tiger-button-primary mt-6">Start shopping ↗</a>
            </div>
        @else
            <div class="mt-8 space-y-4">
                @foreach($orders as $order)
                    <article class="tiger-card p-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between border-b border-[#6b2e12] pb-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <strong class="text-xs font-bold uppercase tracking-wider text-[#faae33]">{{ $order->order_number }}</strong>
                                    <span class="tiger-tag !text-[10px]">{{ strtoupper($order->status) }}</span>
                                    <span class="tiger-tag {{ $order->payment_status === 'paid' ? 'tiger-tag-gold' : 'border-[#faae33] text-[#faae33]' }} !text-[10px]">
                                        {{ strtoupper($order->payment_status) }}
                                    </span>
                                </div>
                                <p class="mt-1 text-[11px] text-[#9f531b]">Placed on {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                            </div>
                            <div class="text-left sm:text-right">
                                <span class="text-[10px] uppercase tracking-wider text-[#9f531b] font-bold">Total</span>
                                <p class="text-lg font-bold text-[#faae33]">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div class="text-xs text-[#faae33] opacity-85">
                                <span class="font-bold text-[#faae33]">{{ $order->items->count() }} item(s)</span>
                                @if($order->items->isNotEmpty())
                                    : {{ $order->items->pluck('product_name')->take(2)->implode(', ') }}@if($order->items->count() > 2)...@endif
                                @endif
                                <span class="block mt-0.5 text-[11px] text-[#9f531b]">Payment: {{ $order->payment?->method }}</span>
                            </div>

                            <div class="flex items-center gap-2">
                                @if($order->payment && $order->payment->status !== 'paid')
                                    <a href="{{ route('payments.show', $order->order_number) }}" class="tiger-button tiger-button-primary text-xs">
                                        Pay now ↗
                                    </a>
                                @endif
                                <a href="{{ route('orders.show', $order->order_number) }}" class="tiger-button tiger-button-ghost text-xs">
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
