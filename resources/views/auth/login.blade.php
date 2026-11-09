@extends('layouts.app')

@section('title', 'Login — '.config('storefront.brand.name'))
@section('meta_description', 'Login to your Daily Books customer account.')

@section('content')
    <section class="mx-auto grid min-h-[calc(100vh-200px)] max-w-5xl items-center gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">
        <div class="hidden lg:block">
            <div class="tiger-card-deep flex min-h-[460px] flex-col justify-between p-8 border border-[#6b2e12]">
                <div class="flex items-center justify-between">
                    <span class="text-4xl font-bold text-[#faae33] font-['Bebas_Neue',sans-serif] tracking-wider">+</span>
                    <span class="tiger-tag-gold">Curated Reader</span>
                </div>
                <div>
                    <p class="text-xs uppercase font-bold tracking-[0.25em] text-[#faae33]/70">{{ config('storefront.brand.name') }} / Reader Guild</p>
                    <h2 class="font-['Bebas_Neue',sans-serif] mt-3 text-4xl uppercase leading-[0.92] tracking-tight text-[#faae33]">
                        Your Next Story Is One Step Away.
                    </h2>
                    <p class="mt-4 text-xs leading-5 text-[#faae33]/80">
                        Access saved volumes, ongoing orders, express checkout presets, and reading progress.
                    </p>
                </div>
                <div class="border-t border-dotted border-[#faae33]/30 pt-4 flex items-center justify-between text-[11px] uppercase tracking-widest text-[#faae33]/60 font-mono">
                    <span>Authentic Books Only</span>
                    <span>100% Genuine</span>
                </div>
            </div>
        </div>

        <div class="tiger-card mx-auto w-full max-w-xl p-8 sm:p-10">
            <p class="text-xs font-bold uppercase tracking-[.25em] text-[#faae33]">Welcome Back</p>
            <h1 class="font-['Bebas_Neue',sans-serif] mt-2 text-4xl sm:text-5xl font-bold uppercase tracking-tight text-[#faae33]">
                Customer Login
            </h1>
            <p class="mt-2 text-xs text-[#faae33]/80">
                Sign in to view your orders, addresses, and wishlist.
            </p>

            <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5 text-[#faae33]">
                @csrf
                <label class="block text-xs font-bold uppercase tracking-wider">
                    Email Address
                    <input name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="mt-1.5 w-full rounded-full border border-[#6b2e12] bg-[#281006] px-4 py-3 text-xs text-[#faae33] placeholder-[#faae33]/40 focus:border-[#faae33] focus:outline-none" placeholder="reader@dailybooks.id">
                    @error('email')
                        <span class="mt-1.5 block text-xs text-[#d1255c] font-medium">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block text-xs font-bold uppercase tracking-wider">
                    Password
                    <input name="password" type="password" required autocomplete="current-password" class="mt-1.5 w-full rounded-full border border-[#6b2e12] bg-[#281006] px-4 py-3 text-xs text-[#faae33] placeholder-[#faae33]/40 focus:border-[#faae33] focus:outline-none" placeholder="••••••••">
                    @error('password')
                        <span class="mt-1.5 block text-xs text-[#d1255c] font-medium">{{ $message }}</span>
                    @enderror
                </label>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-[#faae33]/90">
                        <input type="checkbox" name="remember" value="1" class="accent-[#faae33] rounded">
                        <span>Remember me</span>
                    </label>
                </div>

                <button type="submit" class="tiger-button-primary w-full uppercase py-3 font-bold text-xs tracking-wider">
                    Login To Account ↗
                </button>
            </form>

            <div class="mt-8 border-t border-dotted border-[#faae33]/30 pt-6 text-center text-xs text-[#faae33]/80">
                New to Daily Books? 
                <a href="{{ route('register') }}" class="font-bold text-[#faae33] underline underline-offset-4 hover:text-white transition-colors">
                    Create an account
                </a>
            </div>
        </div>
    </section>
@endsection
