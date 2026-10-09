@php
    $error = match ($status) {
        403 => ['eyebrow' => 'Access restricted', 'title' => 'This space is private.', 'message' => 'You do not have permission to view this page.'],
        419 => ['eyebrow' => 'Session expired', 'title' => 'Try that again.', 'message' => 'Your session expired before the request finished. Refresh and submit once more.'],
        429 => ['eyebrow' => 'Slow down', 'title' => 'Too many requests.', 'message' => 'Please wait a moment before trying again.'],
        500 => ['eyebrow' => 'System pause', 'title' => 'Something went wrong.', 'message' => 'We are looking into it. You can return home and keep browsing.'],
        503 => ['eyebrow' => 'Be right back', 'title' => 'We are tuning the shop.', 'message' => 'Kembang Books is temporarily unavailable for maintenance.'],
        default => ['eyebrow' => 'Page not found', 'title' => 'This page took a different turn.', 'message' => 'The page may have moved, or the link may no longer be available.'],
    };
@endphp

@extends('errors.layout')

@section('title', $error['title'])

@section('content')
    <section class="w-full max-w-2xl border border-black bg-[#f3e5df] p-5 sm:p-8">
        <div class="arcade-checkerboard border border-black p-5"><div class="border border-black bg-[#f3e5df] px-5 py-8 text-center sm:px-10 sm:py-12"><a href="{{ route('home') }}" class="text-lg font-bold uppercase hover:underline">+ KEMBANG BOOKS</a><p class="mt-10 text-7xl font-bold sm:text-9xl">{{ $status }}</p><p class="mt-3 text-xs font-bold uppercase text-[#faa21f]">{{ $error['eyebrow'] }}</p><h1 class="mt-4 text-2xl font-bold uppercase sm:text-4xl">{{ $error['title'] }}</h1><p class="mx-auto mt-4 max-w-md text-sm leading-7 text-[#333333]">{{ $error['message'] }}</p><div class="mt-8 flex flex-wrap justify-center gap-3"><a href="{{ route('home') }}" class="arcade-button arcade-button-primary uppercase">Back home ↗</a><button type="button" onclick="window.history.back()" class="arcade-button arcade-button-ghost uppercase">Go back</button></div></div></div>
    </section>
@endsection
