@extends('layouts.app')

@section('title', 'Payment '.$order->order_number.' — '.config('storefront.brand.name'))

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="text-center">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#faae33]">Payment Gateway</p>
            <h1 class="font-display mt-3 text-5xl uppercase leading-[0.9] tracking-tight text-[#faae33] sm:text-6xl">Complete Your Order</h1>
            <p class="mt-4 text-xs font-semibold uppercase tracking-wider text-[#9f531b]">{{ $order->order_number }} · {{ $order->payment?->method }}</p>
        </div>

        <div class="tiger-card mt-10 p-8">
            <div class="flex items-center justify-between border-b border-[#6b2e12] pb-6">
                <span class="text-xs font-bold uppercase tracking-wider text-[#faae33]">Amount to pay</span>
                <strong class="font-display text-4xl text-[#faae33]">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</strong>
            </div>

            <div class="mt-6 rounded-[6px] border border-[#6b2e12] bg-[#281006] p-6">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider text-[#9f531b]">Payment Instruction</p>
                    <span class="tiger-tag {{ $order->payment?->status === 'paid' ? 'tiger-tag-gold' : 'border-[#faae33] text-[#faae33]' }}">
                        {{ strtoupper($order->payment?->status ?? 'UNPAID') }}
                    </span>
                </div>
                <h2 class="font-display mt-3 text-2xl uppercase tracking-tight text-[#faae33]">{{ $order->payment?->method }}</h2>

                @if($order->payment?->status === 'paid')
                    <p class="mt-4 text-xs leading-6 text-[#faae33] font-bold">
                        ✓ Pembayaran Anda telah terverifikasi dan pesanan Anda sedang disiapkan oleh tim Daily Books.
                    </p>
                @else
                    <p class="mt-4 text-xs leading-6 text-[#faae33] opacity-85">
                        Layar demo ini mensimulasikan integrasi Tripay / Payment Gateway. Dalam lingkungan produksi, instruksi kode bayar (VA/QRIS) atau link redirect akan disediakan di sini.
                    </p>

                    <form method="POST" action="{{ route('payments.confirm', $order->order_number) }}" class="mt-6">
                        @csrf
                        <button type="submit" class="tiger-button tiger-button-primary w-full py-3.5 text-sm">
                            Simulate Payment (Confirm Now) ↗
                        </button>
                    </form>
                @endif
            </div>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('orders.show', $order->order_number) }}" class="tiger-button tiger-button-ghost flex-1 text-center text-xs">
                    View order details
                </a>
                <a href="{{ route('chat.index') }}?order={{ $order->order_number }}" class="tiger-button tiger-button-ghost flex-1 text-center text-xs">
                    Need help with payment?
                </a>
            </div>
        </div>
    </section>
@endsection
