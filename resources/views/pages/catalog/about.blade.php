@extends('layouts.app')

@section('title', 'About — '.config('storefront.brand.name'))
@section('meta_description', 'The story behind Daily Books and our approach to thoughtful reading.')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <div class="grid gap-12 lg:grid-cols-[1fr_380px]">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#faae33]">About the bookstore</p>
                <h1 class="font-display mt-4 max-w-4xl text-6xl uppercase leading-[0.88] tracking-tight text-[#faae33] sm:text-7xl lg:text-8xl">
                    Make room for a new point of view.
                </h1>
                <p class="mt-8 max-w-2xl text-base leading-8 text-[#faae33] opacity-90">
                    {{ config('storefront.brand.name') }} lahir dari keyakinan sederhana: buku yang baik bisa mengubah cara kita melihat satu hari, satu ide, atau satu sama lain.
                </p>
                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="{{ route('products.index') }}" class="tiger-button tiger-button-primary">Browse the shelf ↗</a>
                    <a href="{{ route('chat.index') }}" class="tiger-button tiger-button-ghost">Talk to us</a>
                </div>
            </div>

            <div class="tiger-card-deep flex flex-col justify-between p-8 border border-[#6b2e12]">
                <span class="text-4xl font-bold text-[#faae33]">•</span>
                <div class="mt-12">
                    <p class="text-xs uppercase tracking-widest text-[#9f531b] font-bold">Our Philosophy</p>
                    <p class="font-display mt-2 text-3xl uppercase leading-tight text-[#faae33]">Less noise. More feeling.</p>
                    <p class="mt-3 text-xs leading-6 text-[#faae33] opacity-75">
                        Setiap judul dipilih dengan cermat untuk memberikan nilai mendalam dan tahan lama bagi pembacanya.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="border-y border-dotted border-[#faae33] bg-[#402011]">
        <div class="mx-auto grid max-w-7xl gap-0 sm:grid-cols-3 sm:divide-x sm:divide-[#6b2e12]">
            <article class="px-6 py-12 sm:px-10">
                <span class="font-display text-4xl text-[#faae33]">01</span>
                <h2 class="mt-6 text-xl font-bold uppercase tracking-tight text-[#faae33]">Curated shelves</h2>
                <p class="mt-3 text-xs leading-6 text-[#faae33] opacity-80">Pilihan buku yang dipertimbangkan untuk menemani rasa ingin tahu, bukan sekadar memenuhi rak.</p>
            </article>
            <article class="border-t border-[#6b2e12] px-6 py-12 sm:border-t-0 sm:px-10">
                <span class="font-display text-4xl text-[#faae33]">02</span>
                <h2 class="mt-6 text-xl font-bold uppercase tracking-tight text-[#faae33]">Packed with care</h2>
                <p class="mt-3 text-xs leading-6 text-[#faae33] opacity-80">Setiap pesanan dipacking dengan aman agar cerita sampai di tangan Anda dalam kondisi terbaik.</p>
            </article>
            <article class="border-t border-[#6b2e12] px-6 py-12 sm:border-t-0 sm:px-10">
                <span class="font-display text-4xl text-[#faae33]">03</span>
                <h2 class="mt-6 text-xl font-bold uppercase tracking-tight text-[#faae33]">Read widely</h2>
                <p class="mt-3 text-xs leading-6 text-[#faae33] opacity-80">Fiksi, ide, dan dunia baru untuk setiap suasana hati dan setiap generasi pembaca.</p>
            </article>
        </div>
    </section>

    <section class="mx-auto max-w-3xl px-4 py-24 text-center sm:px-6 lg:px-8">
        <p class="text-xs font-bold uppercase tracking-[0.25em] text-[#faae33]">Keep reading</p>
        <h2 class="font-display mt-4 text-4xl uppercase leading-tight text-[#faae33] sm:text-6xl">Your next chapter starts here.</h2>
        <p class="mt-5 text-sm leading-7 text-[#faae33] opacity-85">Terima kasih sudah menjadi bagian dari perjalanan {{ config('storefront.brand.name') }}.</p>
    </section>
@endsection
