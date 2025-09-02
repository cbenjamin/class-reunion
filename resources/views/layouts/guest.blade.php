<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? config('app.name', 'Reunion') }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-100">
  {{-- Transparent top bar on the homepage (sits over the hero) --}}
  @include('layouts.topnavguest', ['variant' => request()->routeIs('home') ? 'transparent' : 'solid'])

  <main class="min-h-screen">
    {{ $slot }}
  </main>

  @livewireScripts
</body>
</html>