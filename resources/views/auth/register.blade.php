@extends('layouts.app')

@section('title', 'Register — Kembang Books')
@section('meta_description', 'Create a Kembang Books customer account.')

@section('content')
    <section class="mx-auto max-w-xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="arcade-card"><p class="text-xs font-bold uppercase tracking-[.2em] text-[#faa21f]">Join the community</p><h1 class="mt-3 text-4xl font-bold uppercase">Create account</h1><p class="mt-3 text-sm leading-7 text-[#737373]">Save favourites, track orders, and make checkout easier.</p>
            <form method="POST" action="{{ route('register.store') }}" class="mt-8 space-y-5">@csrf<label class="block text-sm">Name<input name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="mt-2 w-full border border-black bg-[#f3e5df] px-3 py-3">@error('name')<span class="mt-2 block text-xs text-red-700">{{ $message }}</span>@enderror</label><label class="block text-sm">Email<input name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="mt-2 w-full border border-black bg-[#f3e5df] px-3 py-3">@error('email')<span class="mt-2 block text-xs text-red-700">{{ $message }}</span>@enderror</label><label class="block text-sm">Phone<input name="phone" value="{{ old('phone') }}" required autocomplete="tel" class="mt-2 w-full border border-black bg-[#f3e5df] px-3 py-3">@error('phone')<span class="mt-2 block text-xs text-red-700">{{ $message }}</span>@enderror</label><div class="grid gap-5 sm:grid-cols-2"><label class="block text-sm">Password<input name="password" type="password" required autocomplete="new-password" class="mt-2 w-full border border-black bg-[#f3e5df] px-3 py-3">@error('password')<span class="mt-2 block text-xs text-red-700">{{ $message }}</span>@enderror</label><label class="block text-sm">Confirm password<input name="password_confirmation" type="password" required autocomplete="new-password" class="mt-2 w-full border border-black bg-[#f3e5df] px-3 py-3"></label></div><label class="flex items-start gap-2 text-sm"><input type="checkbox" name="terms" value="1" class="mt-1"> <span>I agree to the customer terms and privacy policy.</span></label>@error('terms')<span class="block text-xs text-red-700">{{ $message }}</span>@enderror<button type="submit" class="arcade-button arcade-button-primary w-full uppercase">Create account ↗</button></form>
            <p class="mt-6 text-center text-sm text-[#737373]">Already a customer? <a href="{{ route('login') }}" class="font-bold text-black hover:underline">Login</a></p>
        </div>
    </section>
@endsection
