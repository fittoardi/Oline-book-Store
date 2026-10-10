@extends('layouts.app')

@section('title', 'Payment '.$order->order_number.' — Kembang Books')

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="text-center"><p class="text-xs font-bold uppercase tracking-[.2em] text-[#faa21f]">Payment</p><h1 class="mt-3 text-4xl font-bold uppercase">Complete your order</h1><p class="mt-4 text-sm text-[#737373]">{{ $order->order_number }} · {{ $order->payment?->method }}</p></div>
        <div class="arcade-card mt-8"><div class="flex items-center justify-between border-b border-black pb-5"><span class="text-sm uppercase">Amount to pay</span><strong class="text-2xl">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</strong></div><div class="mt-6 border border-black p-5"><p class="text-xs font-bold uppercase text-[#737373]">Payment instruction</p><h2 class="mt-3 text-lg font-bold uppercase">{{ $order->payment?->method }}</h2><p class="mt-3 text-sm leading-6 text-[#737373]">This demo payment screen is ready for Tripay integration. In production, the gateway response will provide the virtual account, QR code, or redirect URL here.</p></div><div class="mt-6 flex flex-col gap-3 sm:flex-row"><a href="{{ route('orders.show', $order->order_number) }}" class="arcade-button arcade-button-primary flex-1 justify-center uppercase">View order</a><a href="{{ route('chat.index') }}?order={{ $order->order_number }}" class="arcade-button arcade-button-ghost flex-1 justify-center uppercase">Need help?</a></div></div>
    </section>
@endsection
