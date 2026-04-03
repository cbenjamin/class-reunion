@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title . ' — ' . config('app.name') : config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet">
    @include('layouts.partials.assets')
    @livewireStyles
</head>
<body class="min-h-screen bg-gradient-to-br from-red-950 via-red-900 to-gray-900 font-sans antialiased">

    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-12">

        {{-- Brand logo --}}
        <div class="mb-8 text-center">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo.svg') }}"
                     alt="{{ config('app.name') }}"
                     class="h-12 w-auto mx-auto brightness-0 invert">
            </a>
        </div>

        {{-- White card --}}
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-6 md:p-8">
            {{ $slot }}
        </div>

    </div>

    @livewireScripts
    @fluxScripts
</body>
</html>
