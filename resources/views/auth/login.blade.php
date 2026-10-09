@extends('layouts.app')

@section('title', 'Login — Kembang Books')
@section('meta_description', 'Login to your Kembang Books customer account.')

@section('content')
    <section class="mx-auto grid min-h-[calc(100vh-180px)] max-w-5xl items-center gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[.9fr_1.1fr] lg:px-8">
        <div class="hidden border border-black p-5 lg:block"><div class="arcade-checkerboard flex min-h-[440px] flex-col justify-between border border-black bg-[#f3e5df] p-6"><span class="text-5xl font-bold">+</span><div><p class="text-xs uppercase text-[#737373]">Kembang Books / Customer account</p><p class="mt-3 text-3xl font-bold uppercase">Your next story is one login away.</p></div></div></div>
        <div class="arcade-card mx-auto w-full max-w-xl"><p class="text-xs font-bold uppercase tracking-[.2em] text-[#faa21f]">Welcome back</p><h1 class="mt-3 text-4xl font-bold uppercase">Login</h1><p class="mt-3 text-sm leading-7 text-[#737373]">Sign in to see your saved pieces and order history.</p>
            <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">@csrf<label class="block text-sm">Email<input name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="mt-2 w-full border border-black bg-[#f3e5df] px-3 py-3">@error('email')<span class="mt-2 block text-xs text-red-700">{{ $message }}</span>@enderror</label><label class="block text-sm">Password<input name="password" type="password" required autocomplete="current-password" class="mt-2 w-full border border-black bg-[#f3e5df] px-3 py-3">@error('password')<span class="mt-2 block text-xs text-red-700">{{ $message }}</span>@enderror</label><label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember" value="1"> Remember me</label><button type="submit" class="arcade-button arcade-button-primary w-full uppercase">Login ↗</button></form>
            <p class="mt-6 text-center text-sm text-[#737373]">New to Kembang? <a href="{{ route('register') }}" class="font-bold text-black hover:underline">Create an account</a></p>
        </div>
    </section>
@endsection
