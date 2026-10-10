@extends('layouts.app')

@section('title', 'Orders — '.config('storefront.brand.name'))

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="border-b border-black pb-6"><p class="text-xs font-bold uppercase tracking-[.2em] text-[#faa21f]">Your account</p><h1 class="mt-3 text-4xl font-bold uppercase">Order history</h1></div>
        @if(session('status'))<div class="mt-6 border border-black bg-[#faa21f] px-4 py-3 text-sm">{{ session('status') }}</div>@endif
        @if($orders->isEmpty())
            <div class="arcade-card mt-8 py-16 text-center"><p class="text-lg font-bold uppercase">No orders yet.</p><p class="mt-2 text-sm text-[#737373]">Your next favourite book is waiting.</p><a href="{{ route('products.index') }}" class="arcade-button arcade-button-primary mt-6 inline-flex uppercase">Start shopping</a></div>
        @else
            <div class="mt-8 space-y-3">@foreach($orders as $order)<a href="{{ route('orders.show', $order->order_number) }}" class="arcade-card block transition hover:border-black"><div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"><div><p class="text-xs text-[#737373]">{{ $order->order_number }}</p><h2 class="mt-1 text-lg font-bold uppercase">{{ $order->items->count() }} item(s)</h2><p class="mt-1 text-sm text-[#737373]">{{ $order->payment?->method }} · {{ ucfirst($order->status) }}</p></div><div class="text-left sm:text-right"><p class="text-lg font-bold">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p><span class="mt-2 inline-flex arcade-tag">View details ↗</span></div></div></a>@endforeach</div>
        @endif
    </section>
@endsection
