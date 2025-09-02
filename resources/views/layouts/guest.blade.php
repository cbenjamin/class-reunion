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
<body
  x-data="{ navOpen: false }"
  x-effect="document.documentElement.classList.toggle('overflow-hidden', navOpen)"
  class="font-sans antialiased bg-gray-100"
>
  {{-- Transparent on homepage, solid elsewhere --}}
  @include('layouts.topnav', ['variant' => request()->routeIs('home') ? 'transparent' : 'solid'])

  <main class="min-h-screen">
    {{ $slot }}
  </main>

  @livewireScripts
</body>
</html>