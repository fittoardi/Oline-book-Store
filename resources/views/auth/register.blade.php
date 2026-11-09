@extends('layouts.app')

@section('title', 'Register — '.config('storefront.brand.name'))
@section('meta_description', 'Create a Daily Books customer account.')

@section('content')
    <section class="mx-auto max-w-xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="tiger-card p-8 sm:p-10">
            <p class="text-xs font-bold uppercase tracking-[.25em] text-[#faae33]">Join The Guild</p>
            <h1 class="font-['Bebas_Neue',sans-serif] mt-2 text-4xl sm:text-5xl font-bold uppercase tracking-tight text-[#faae33]">
                Create Account
            </h1>
            <p class="mt-2 text-xs text-[#faae33]/80">
                Save favourites, track deliveries, and expedite checkout.
            </p>

            <form method="POST" action="{{ route('register.store') }}" class="mt-8 space-y-4 text-[#faae33]">
                @csrf
                <label class="block text-xs font-bold uppercase tracking-wider">
                    Full Name
                    <input name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="mt-1.5 w-full rounded-full border border-[#6b2e12] bg-[#281006] px-4 py-3 text-xs text-[#faae33] placeholder-[#faae33]/40 focus:border-[#faae33] focus:outline-none" placeholder="Budi Santoso">
                    @error('name')
                        <span class="mt-1.5 block text-xs text-[#d1255c] font-medium">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block text-xs font-bold uppercase tracking-wider">
                    Email Address
                    <input name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="mt-1.5 w-full rounded-full border border-[#6b2e12] bg-[#281006] px-4 py-3 text-xs text-[#faae33] placeholder-[#faae33]/40 focus:border-[#faae33] focus:outline-none" placeholder="budi@example.com">
                    @error('email')
                        <span class="mt-1.5 block text-xs text-[#d1255c] font-medium">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block text-xs font-bold uppercase tracking-wider">
                    Phone Number
                    <input name="phone" value="{{ old('phone') }}" required autocomplete="tel" class="mt-1.5 w-full rounded-full border border-[#6b2e12] bg-[#281006] px-4 py-3 text-xs text-[#faae33] placeholder-[#faae33]/40 focus:border-[#faae33] focus:outline-none" placeholder="08123456789">
                    @error('phone')
                        <span class="mt-1.5 block text-xs text-[#d1255c] font-medium">{{ $message }}</span>
                    @enderror
                </label>

                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="block text-xs font-bold uppercase tracking-wider">
                        Password
                        <input name="password" type="password" required autocomplete="new-password" class="mt-1.5 w-full rounded-full border border-[#6b2e12] bg-[#281006] px-4 py-3 text-xs text-[#faae33] placeholder-[#faae33]/40 focus:border-[#faae33] focus:outline-none" placeholder="••••••••">
                        @error('password')
                            <span class="mt-1.5 block text-xs text-[#d1255c] font-medium">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="block text-xs font-bold uppercase tracking-wider">
                        Confirm Password
                        <input name="password_confirmation" type="password" required autocomplete="new-password" class="mt-1.5 w-full rounded-full border border-[#6b2e12] bg-[#281006] px-4 py-3 text-xs text-[#faae33] placeholder-[#faae33]/40 focus:border-[#faae33] focus:outline-none" placeholder="••••••••">
                    </label>
                </div>

                <label class="flex items-start gap-2.5 text-xs text-[#faae33]/85 pt-2 cursor-pointer">
                    <input type="checkbox" name="terms" value="1" class="mt-0.5 accent-[#faae33] rounded">
                    <span>I agree to the Daily Books customer terms and privacy policy.</span>
                </label>
                @error('terms')
                    <span class="block text-xs text-[#d1255c] font-medium">{{ $message }}</span>
                @enderror

                <button type="submit" class="tiger-button-primary w-full uppercase py-3 font-bold text-xs tracking-wider mt-4">
                    Create Account ↗
                </button>
            </form>

            <div class="mt-8 border-t border-dotted border-[#faae33]/30 pt-6 text-center text-xs text-[#faae33]/80">
                Already registered with Daily Books? 
                <a href="{{ route('login') }}" class="font-bold text-[#faae33] underline underline-offset-4 hover:text-white transition-colors">
                    Login
                </a>
            </div>
        </div>
    </section>
@endsection
