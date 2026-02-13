<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? config('app.name', 'Reunion') }}</title>

  @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" referrerpolicy="no-referrer">

  @livewireStyles
  @stack('head')
  @auth
    <!-- PushAlert Unified Code -->
    <script type="text/javascript">
      (function(d, t) {
        var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
        g.src = "https://cdn.pushalert.co/unified_add2e5f6d22347f2320815f3cd215889.js";
        s.parentNode.insertBefore(g, s);
      }(document, "script"));
    </script>
    <!-- End PushAlert Unified Code -->
  @endauth
</head>

@php
  // Drawer on home only; fixed sidebar on all other pages
  $useDrawer = request()->routeIs('home');
  // Control hamburger visibility in topnav: 'always' (home) vs 'mobile' (others)
  $hamburgerMode = $useDrawer ? 'always' : 'mobile';
@endphp

<body class="font-sans antialiased bg-gray-100"
      x-data="{ drawer:false }"
      @keydown.window.escape="drawer=false">

  {{-- Top bar (pass hamburger mode) --}}
  @include('layouts.topnav', ['variant' => 'solid', 'hamburgerMode' => $hamburgerMode])

  {{-- Drawer is available everywhere (great for mobile).
       On home it’s the primary nav; elsewhere it’s just the mobile nav. --}}
  @include('layouts.drawer')

  {{-- Fixed sidebar on all pages except home (md+) --}}
  @auth
    @unless($useDrawer)
      @include('layouts.sidebar')
    @endunless
  @endauth

  {{-- Main content. Add left padding only when sidebar is present. --}}
  <main class="min-h-screen transition-[padding] {{ $useDrawer ? '' : 'md:pl-72' }}">
    {{ $slot }}
  </main>

  @livewireScripts
  @stack('scripts')
</body>
</html>