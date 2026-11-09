@extends('layouts.app')

@section('title', 'Customer care — '.config('storefront.brand.name'))
@section('meta_description', 'Talk to the Daily Books team about titles, orders, and delivery.')

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <div class="grid gap-8 lg:grid-cols-[.8fr_1.2fr]">
            {{-- Information Panel --}}
            <div class="rounded-[6px] border border-[#6b2e12] bg-[#402011] p-6 sm:p-8">
                <div class="flex items-center justify-between border-b border-[#6b2e12] pb-5">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#9f531b]">Customer Care</p>
                        <h1 class="font-display mt-2 text-4xl uppercase leading-[0.9] tracking-tight text-[#faae33]">Find Your Next Read.</h1>
                    </div>
                    <span class="tiger-tag tiger-tag-gold">OPEN</span>
                </div>

                <p class="mt-6 text-xs leading-6 text-[#faae33] opacity-85">
                    Tanyakan rekomendasi buku, status order, pembayaran, atau pengiriman hadiah. Tim kami siap menemani proses membaca Anda.
                </p>

                <div class="mt-8 space-y-2">
                    @foreach(config('storefront.support.topics') as $topic)
                        <div class="flex items-center gap-3 border-b border-[#6b2e12] py-3 text-xs">
                            <span class="font-bold text-[#faae33]">0{{ $loop->iteration }}</span>
                            <span class="uppercase font-bold text-[#faae33]">{{ $topic }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 pt-4 border-t border-[#6b2e12] text-[11px] text-[#faae33] opacity-75 space-y-1">
                    <p>Operating Hours: {{ config('storefront.support.hours') }} {{ config('storefront.support.timezone') }}</p>
                    <p>Response Time: {{ config('storefront.support.response_time') }}</p>
                </div>

                <a href="mailto:{{ config('storefront.brand.email') }}" class="tiger-button tiger-button-ghost mt-6 w-full text-center">
                    Email Team Direct ↗
                </a>
            </div>

            {{-- Chat / Message Thread & Composer --}}
            <div class="tiger-card-deep border border-[#6b2e12] p-6 sm:p-8">
                @if(session('status'))
                    <div class="mb-6 rounded-[6px] border border-[#faae33] bg-[#faae33] px-4 py-3 text-xs font-bold uppercase text-[#281006]">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="flex items-end justify-between gap-4 border-b border-[#6b2e12] pb-5">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#9f531b]">Direct Conversation</p>
                        <h2 class="font-display mt-2 text-3xl uppercase tracking-tight text-[#faae33]">How Can We Help?</h2>
                    </div>
                    <span class="text-xl text-[#faae33]">•</span>
                </div>

                {{-- Previous Messages if Authenticated --}}
                @if(isset($messages) && $messages->isNotEmpty())
                    <div class="my-6 max-h-72 space-y-3 overflow-y-auto rounded-[6px] border border-[#6b2e12] bg-[#402011] p-4">
                        @foreach($messages as $msg)
                            <div class="text-xs {{ $msg->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                                <div class="inline-block max-w-[85%] rounded-[6px] border border-[#6b2e12] p-3 {{ $msg->sender_id === auth()->id() ? 'bg-[#faae33] text-[#281006]' : 'bg-[#281006] text-[#faae33]' }}">
                                    <p class="font-bold uppercase text-[10px]">{{ $msg->sender?->name ?? 'Daily Books Support' }}</p>
                                    <p class="mt-1 text-xs">{{ $msg->message }}</p>
                                    <span class="mt-2 block text-[9px] opacity-70">{{ $msg->created_at->format('H:i') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('chat.submit') }}" class="mt-5">
                    @csrf
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#faae33]" for="chat-message">Your message</label>
                    <textarea id="chat-message" name="message" rows="5" required class="mt-2 w-full resize-none" placeholder="Tell us what you are looking for or mention your order number..."></textarea>
                    @error('message')
                        <p class="mt-2 text-xs text-[#d1255c]">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="tiger-button tiger-button-primary mt-4 w-full">
                        Send to customer care ↗
                    </button>
                </form>

                <p class="mt-4 text-center text-xs text-[#faae33] opacity-70">
                    Typical response time: {{ config('storefront.support.response_time') }}
                </p>
            </div>
        </div>
    </section>
@endsection
