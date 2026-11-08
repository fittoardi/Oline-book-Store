@extends('layouts.app')

@section('title', 'Customer care — '.config('storefront.brand.name'))
@section('meta_description', 'Talk to the Daily Books team about titles, orders, and delivery.')

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8 lg:py-20">
        <div class="grid gap-8 lg:grid-cols-[.8fr_1.2fr]">
            {{-- Information Panel --}}
            <div class="border border-black bg-[#f3e5df] p-6 sm:p-8">
                <div class="flex items-center justify-between border-b border-black pb-5">
                    <div>
                        <p class="text-xs font-bold uppercase text-[#faa21f]">Customer care</p>
                        <h1 class="mt-2 text-3xl font-bold uppercase">Find your next read.</h1>
                    </div>
                    <span class="arcade-tag bg-[#faa21f]">OPEN</span>
                </div>

                <p class="mt-6 text-sm leading-7 text-[#333333]">
                    Tanyakan rekomendasi buku, status order, pembayaran, atau pengiriman hadiah. Tim kami siap menemani proses membaca Anda.
                </p>

                <div class="mt-8 space-y-2">
                    @foreach(config('storefront.support.topics') as $topic)
                        <div class="flex items-center gap-3 border-b border-[#e5e7eb] py-3 text-sm">
                            <span class="text-[#faa21f] font-bold">0{{ $loop->iteration }}</span>
                            <span class="uppercase font-bold">{{ $topic }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 pt-4 border-t border-black text-xs text-[#737373] space-y-1">
                    <p>Operating Hours: {{ config('storefront.support.hours') }} {{ config('storefront.support.timezone') }}</p>
                    <p>Response Time: {{ config('storefront.support.response_time') }}</p>
                </div>

                <a href="mailto:{{ config('storefront.brand.email') }}" class="arcade-button arcade-button-ghost mt-6 inline-flex uppercase text-xs">
                    Email Team Direct ↗
                </a>
            </div>

            {{-- Chat / Message Thread & Composer --}}
            <div class="arcade-checkerboard border border-black p-5 sm:p-8">
                <div class="border border-black bg-[#f3e5df] p-6 sm:p-8">
                    @if(session('status'))
                        <div class="mb-6 border border-black bg-[#faa21f] px-4 py-3 text-sm font-bold text-black">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="flex items-end justify-between gap-4 border-b border-black pb-5">
                        <div>
                            <p class="text-xs font-bold uppercase text-[#faa21f]">Direct Conversation</p>
                            <h2 class="mt-2 text-2xl font-bold uppercase">How can we help?</h2>
                        </div>
                        <span class="text-2xl font-bold">+</span>
                    </div>

                    {{-- Previous Messages if Authenticated --}}
                    @if(isset($messages) && $messages->isNotEmpty())
                        <div class="my-6 max-h-72 space-y-3 overflow-y-auto border border-[#e5e7eb] p-4 bg-[#f3e5df]">
                            @foreach($messages as $msg)
                                <div class="text-xs {{ $msg->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                                    <div class="inline-block max-w-[85%] border border-black p-3 {{ $msg->sender_id === auth()->id() ? 'bg-[#faa21f] text-black' : 'bg-white text-black' }}">
                                        <p class="font-bold uppercase text-[10px]">{{ $msg->sender?->name ?? 'Daily Books Support' }}</p>
                                        <p class="mt-1 text-sm">{{ $msg->message }}</p>
                                        <span class="mt-2 block text-[9px] opacity-70">{{ $msg->created_at->format('H:i') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('chat.submit') }}" class="mt-5">
                        @csrf
                        <label class="block text-xs font-bold uppercase" for="chat-message">Your message</label>
                        <textarea id="chat-message" name="message" rows="5" required class="mt-2 w-full resize-none border border-black bg-[#f3e5df] p-3 text-sm" placeholder="Tell us what you are looking for or mention your order number..."></textarea>
                        @error('message')
                            <p class="mt-2 text-xs text-red-700">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="arcade-button arcade-button-primary mt-4 w-full uppercase font-bold py-3">
                            Send to customer care ↗
                        </button>
                    </form>

                    <p class="mt-4 text-center text-xs text-[#737373]">
                        Typical response time: {{ config('storefront.support.response_time') }}
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
