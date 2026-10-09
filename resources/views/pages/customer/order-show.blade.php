@extends('layouts.app')

@section('title', $order['number'].' — Kembang Books')

@section('content')
    <section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <a href="{{ route('orders.index') }}" class="text-sm font-bold uppercase hover:underline">← Back to orders</a>
        <div class="mt-6 flex flex-col gap-4 border-b border-black pb-6 sm:flex-row sm:items-end sm:justify-between"><div><p class="text-xs font-bold uppercase tracking-[.2em] text-[#faa21f]">Order detail</p><h1 class="mt-3 text-4xl font-bold uppercase">{{ $order['number'] }}</h1></div><span class="arcade-tag">{{ strtoupper($order['status']) }}</span></div>
        <div class="mt-8 grid gap-5 lg:grid-cols-[1fr_300px]">
            <div class="space-y-5"><section class="arcade-card"><h2 class="text-lg font-bold uppercase">Items</h2><div class="mt-5 space-y-4">@foreach($order['items'] as $item)<div class="flex gap-3 border-b border-[#e5e7eb] pb-4 last:border-0 last:pb-0"><img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" width="64" height="80" class="h-20 w-16 object-cover"><div class="flex-1"><h3 class="text-sm font-bold uppercase">{{ $item['name'] }}</h3><p class="mt-1 text-sm text-[#737373]">{{ $item['quantity'] }} x Rp{{ number_format($item['price'], 0, ',', '.') }}</p></div><span class="text-sm">Rp{{ number_format($item['quantity'] * $item['price'], 0, ',', '.') }}</span></div>@endforeach</div></section><section class="arcade-card"><h2 class="text-lg font-bold uppercase">Delivery</h2><p class="mt-4 text-sm leading-6">{{ $order['customer']['name'] }}<br>{{ $order['customer']['phone'] }}<br>{{ $order['customer']['address'] }}</p></section></div>
            <aside class="arcade-card h-fit"><h2 class="text-lg font-bold uppercase">Summary</h2><div class="mt-5 space-y-3 border-b border-black pb-4 text-sm"><div class="flex justify-between"><span>Subtotal</span><span>Rp{{ number_format($order['subtotal'], 0, ',', '.') }}</span></div><div class="flex justify-between"><span>Shipping</span><span>Rp{{ number_format($order['shipping'], 0, ',', '.') }}</span></div></div><div class="mt-4 flex justify-between text-lg font-bold"><span>Total</span><span>Rp{{ number_format($order['total'], 0, ',', '.') }}</span></div><p class="mt-5 border-t border-[#e5e7eb] pt-4 text-xs text-[#737373]">Payment: {{ $order['payment'] }}<br>Status: {{ $order['status'] }}</p>@if($order['payment'] !== 'COD')<a href="{{ route('payments.show', $order['number']) }}" class="arcade-button arcade-button-primary mt-5 flex justify-center uppercase">Complete payment</a>@endif<a href="{{ route('chat.index') }}?order={{ $order['number'] }}" class="arcade-button arcade-button-ghost mt-3 flex justify-center uppercase">Contact customer care</a></aside>
        </div>
    </section>
@endsection
