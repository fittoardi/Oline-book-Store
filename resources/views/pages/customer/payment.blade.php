@extends('layouts.app')

@section('title', 'Payment '.$order->order_number.' — '.config('storefront.brand.name'))

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="text-center">
            <p class="text-xs font-bold uppercase tracking-[.2em] text-[#faa21f]">Payment Gateway</p>
            <h1 class="mt-3 text-4xl font-bold uppercase">Complete your order</h1>
            <p class="mt-4 text-sm text-[#737373]">{{ $order->order_number }} · {{ $order->payment?->method }}</p>
        </div>

        <div class="arcade-card mt-8">
            <div class="flex items-center justify-between border-b border-black pb-5">
                <span class="text-sm font-bold uppercase">Amount to pay</span>
                <strong class="text-2xl font-bold">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</strong>
            </div>

            <div class="mt-6 border border-black p-5 bg-[#f3e5df]">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase text-[#737373]">Payment instruction</p>
                    <span class="arcade-tag {{ $order->payment?->status === 'paid' ? 'bg-[#128e44] text-white' : 'bg-[#faa21f]' }}">
                        {{ strtoupper($order->payment?->status ?? 'UNPAID') }}
                    </span>
                </div>
                <h2 class="mt-3 text-lg font-bold uppercase">{{ $order->payment?->method }}</h2>

                @if($order->payment?->status === 'paid')
                    <p class="mt-3 text-sm leading-6 text-[#128e44] font-bold">
                        ✓ Pembayaran Anda telah terverifikasi dan pesanan Anda sedang disiapkan oleh tim Kembang Books.
                    </p>
                @else
                    <p class="mt-3 text-sm leading-6 text-[#737373]">
                        Layar demo ini mensimulasikan integrasi Tripay / Payment Gateway. Dalam lingkungan produksi, instruksi kode bayar (VA/QRIS) atau link redirect akan disediakan di sini.
                    </p>

                    <form method="POST" action="{{ route('payments.confirm', $order->order_number) }}" class="mt-5">
                        @csrf
                        <button type="submit" class="arcade-button arcade-button-primary w-full uppercase font-bold py-3 text-sm">
                            Simulate Payment (Confirm Now) ↗
                        </button>
                    </form>
                @endif
            </div>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('orders.show', $order->order_number) }}" class="arcade-button arcade-button-ghost flex-1 text-center uppercase py-3 text-xs font-bold">
                    View order details
                </a>
                <a href="{{ route('chat.index') }}?order={{ $order->order_number }}" class="arcade-button arcade-button-ghost flex-1 text-center uppercase py-3 text-xs">
                    Need help with payment?
                </a>
            </div>
        </div>
    </section>
@endsection
