<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'K UI') }}</title>

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />

    <!-- Styles -->
    <style>
        [x-cloak] {
            display: none;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div x-data="mainState" x-on:resize.window="handleWindowResize" x-cloak class="flex flex-col min-h-screen">
        <x-navbar />
        <div class="flex flex-col items-center justify-center flex-grow mt-2 text-gray-200 bg-dark-eval-0">
            {{-- @if (Auth::user()?->created_at && now()->diffInMinutes(Auth::user()->created_at, true) <= 10) --}}
            <div class="fixed z-10 px-4 py-2 text-2xl rounded-md shadow-inner top-6 text-tertiary-txt bg-primary-bg"
                role="alert">
                <div class="flex flex-col items-center">
                    <p>
                        {{ __('Hi') }} {{ Auth::user()->first_name }}! {{ __('Wanna know a bit about us') }}?
                    </p>
                    <a class="mt-2 text-center shadow-inner text-secondary-txt hover:underline"
                        href="{{ route('home') }}">{{ __('Click here!') }}</a>
                </div>
            </div>
            {{-- @endif --}}
            <div class="flex flex-col items-center justify-center flex-grow gap-4 px-0 sm:px-6 md:px-8">
                <header class="flex flex-col items-center gap-3 mb-8 text-center lg:flex-row">
                    <h2 class="flex-1 font-bold text-8xl text-secondary-txt">{{ __('Welcome') }}</h2>
                    <h1 class="flex-1 text-3xl">
                        {{ __('Your guardian will be with you - and your finances, in a good sense.') }}
                    </h1>
                </header>

                <div class="flex flex-col items-center justify-center gap-4">
                    <a type="button" href="{{ route('dashboard') }}"
                        class="inline-block px-4 py-2 rounded-md shadow-inner text-tertiary-txt hover:shadow-innerHover hover:text-secondary-txt">{{ __('Dashboard') }}</a>

                </div>
            </div>
        </div>
        <!-- Page Footer -->
        <div class="relative items-center justify-center hidden md:flex">
            <x-application-logo class="absolute bottom-0 w-16 h-16 mb-8 transition-all duration-200 hover:bottom-3" />
        </div>
    </div>
</body>

</html>
