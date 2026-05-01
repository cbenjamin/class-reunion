@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? config('app.name', 'Reunion') }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-JFCHVG2B2R"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-JFCHVG2B2R');
  </script>
  @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-100">
  @include('layouts.navigation')
  <main class="min-h-screen">
    {{ $slot }}
  </main>
  @livewireScripts
</body>
</html>