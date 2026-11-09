@php
    $error = match ($status) {
        403 => ['eyebrow' => 'Access Restricted', 'title' => 'This Space Is Private.', 'message' => 'You do not have permission to view this section of the archive.'],
        419 => ['eyebrow' => 'Session Expired', 'title' => 'Try That Again.', 'message' => 'Your security token expired before the request completed. Refresh and submit once more.'],
        429 => ['eyebrow' => 'Rate Limited', 'title' => 'Too Many Requests.', 'message' => 'Please take a pause before requesting additional volumes from the shelf.'],
        500 => ['eyebrow' => 'System Pause', 'title' => 'Something Went Wrong.', 'message' => 'We are tuning the press. Return to the storefront catalog and continue browsing.'],
        503 => ['eyebrow' => 'Maintenance Mode', 'title' => 'Tuning The Shop.', 'message' => ''.config('storefront.brand.name').' is temporarily undergoing scheduled shelf replenishment.'],
        default => ['eyebrow' => 'Page Not Found', 'title' => 'This page took a different turn.', 'message' => 'The volume may have relocated, or the link may no longer be available in our catalog.'],
    };
@endphp

@extends('errors.layout')

@section('title', $error['title'])

@section('content')
    <section class="w-full max-w-2xl">
        <div class="tiger-card-deep p-6 sm:p-10 border border-[#6b2e12]">
            <div class="rounded-[6px] border border-[#6b2e12] bg-[#402011] px-6 py-10 text-center sm:px-12 sm:py-14">
                <a href="{{ route('home') }}" class="inline-block text-xs font-bold uppercase tracking-[0.25em] text-[#faae33] hover:text-white transition-colors">
                    + {{ strtoupper(config('storefront.brand.name')) }}
                </a>

                <p class="mt-6 font-['Bebas_Neue',sans-serif] text-8xl font-bold leading-none tracking-tight text-[#faae33] sm:text-9xl">
                    {{ $status }}
                </p>

                <p class="mt-3 text-xs font-bold uppercase tracking-[0.2em] text-[#9f531b]">
                    {{ $error['eyebrow'] }}
                </p>

                <h1 class="font-['Bebas_Neue',sans-serif] mt-3 text-3xl font-bold uppercase tracking-tight text-[#faae33] sm:text-5xl leading-tight">
                    {{ $error['title'] }}
                </h1>

                <p class="mx-auto mt-4 max-w-md text-xs sm:text-sm leading-6 text-[#faae33]/80">
                    {{ $error['message'] }}
                </p>

                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('home') }}" class="tiger-button-primary uppercase text-xs font-bold px-6 py-3 tracking-wider">
                        Back Home ↗
                    </a>
                    <button type="button" onclick="window.history.back()" class="tiger-button-ghost uppercase text-xs font-bold px-6 py-3 tracking-wider">
                        Go Back
                    </button>
                </div>
            </div>
        </div>
    </section>
@endsection
