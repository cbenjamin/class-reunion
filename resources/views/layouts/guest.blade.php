<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}"> 
  <title>{{ $title ?? config('app.name', 'Reunion') }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  @livewireStyles
  <script>
  if ('scrollRestoration' in history) { history.scrollRestoration = 'manual'; }
  </script>
</head>
<body class="font-sans antialiased bg-gray-100">
  @include('layouts.navigation')

  <!-- Let each page control its own max width -->
  <main class="min-h-screen">
    {{ $slot }}
  </main>

  @livewireScripts
</body>
</html>