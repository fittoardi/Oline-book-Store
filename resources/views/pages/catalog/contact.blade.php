@extends('layouts.app')

@section('title', 'Contact — '.config('storefront.brand.name'))
@section('meta_description', 'Contact the Daily Books team about titles, orders, and delivery.')

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <div class="grid gap-8 lg:grid-cols-[.8fr_1.2fr]">
            <div class="tiger-card-deep flex min-h-[520px] flex-col justify-between border border-[#6b2e12] p-6 sm:p-10">
                <div class="flex items-start justify-between">
                    <span class="tiger-tag tiger-tag-gold">CONTACT / 01</span>
                    <span class="text-sm text-[#faae33]">•</span>
                </div>
                <div class="rounded-[6px] border border-[#6b2e12] bg-[#402011] p-6 sm:p-8">
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#9f531b]">Daily Books</p>
                    <h1 class="font-display mt-3 text-4xl uppercase leading-[0.9] tracking-tight text-[#faae33] sm:text-5xl">Let Us Talk Books.</h1>
                    <p class="mt-4 text-xs leading-6 text-[#faae33] opacity-85">Cari judul tertentu, butuh rekomendasi, atau ingin tahu status order? Tim kami siap membantu.</p>
                </div>
                <div class="flex justify-between text-xs font-bold uppercase tracking-wider text-[#faae33] opacity-80">
                    <span>{{ config('storefront.support.hours') }}</span>
                    <span>{{ config('storefront.support.timezone') }}</span>
                </div>
            </div>

            <div class="rounded-[6px] border border-[#6b2e12] bg-[#402011] p-6 sm:p-10">
                <div class="flex items-end justify-between gap-4 border-b border-[#6b2e12] pb-5">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#9f531b]">Direct Line</p>
                        <h2 class="font-display mt-1 text-3xl uppercase tracking-tight text-[#faae33]">Send Us A Note</h2>
                    </div>
                    <span class="text-xl text-[#faae33]">↗</span>
                </div>

                @if(session('status'))
                    <div class="mt-5 rounded-[6px] border border-[#faae33] bg-[#faae33] px-4 py-3 text-xs font-bold uppercase text-[#281006]">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="mt-7 space-y-4" action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#faae33]">
                            Name
                            <input class="mt-2 w-full" type="text" name="name" value="{{ old('name') }}" required>
                        </label>
                        <label class="block text-xs font-bold uppercase tracking-wider text-[#faae33]">
                            Email
                            <input class="mt-2 w-full" type="email" name="email" value="{{ old('email') }}" required>
                        </label>
                    </div>

                    <label class="block text-xs font-bold uppercase tracking-wider text-[#faae33]">
                        Subject
                        <input class="mt-2 w-full" type="text" name="subject" value="{{ old('subject') }}" required>
                    </label>

                    <label class="block text-xs font-bold uppercase tracking-wider text-[#faae33]">
                        Message
                        <textarea class="mt-2 min-h-36 w-full" name="message" required>{{ old('message') }}</textarea>
                    </label>

                    <button class="tiger-button tiger-button-primary mt-6 w-full sm:w-auto" type="submit">Send message ↗</button>
                </form>

                <div class="mt-8 grid gap-3 border-t border-[#6b2e12] pt-6 text-xs sm:grid-cols-2">
                    <a href="mailto:{{ config('storefront.brand.email') }}" class="tiger-button tiger-button-ghost">Email ↗</a>
                    <a href="{{ route('chat.index') }}" class="tiger-button tiger-button-ghost">Customer care ↗</a>
                </div>
            </div>
        </div>
    </section>
@endsection
