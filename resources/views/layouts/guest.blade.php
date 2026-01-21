<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? config('app.name', 'Reunion') }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  @stack('styles')
  @livewireStyles
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" referrerpolicy="no-referrer">
 @auth
    <!-- PushAlert Unified Code -->
  <script type="text/javascript">
      (function(d, t) {
          var g = d.createElement(t),
          s = d.getElementsByTagName(t)[0];
          g.src = "https://cdn.pushalert.co/unified_add2e5f6d22347f2320815f3cd215889.js";
          s.parentNode.insertBefore(g, s);
      }(document, "script"));
  </script>
  <!-- End PushAlert Unified Code -->  
@endauth  
</head>
<body class="font-sans antialiased bg-gray-100" x-data="{ drawer:false }" @keydown.window.escape="drawer=false">
  {{-- Top bar (transparent/solid controlled by caller) --}}
  @include('layouts.topnav')

  {{-- Drawer is included but only renders for @auth --}}
  @include('layouts.drawer')

  <main class="min-h-screen">
    {{ $slot }}
  </main>

  @livewireScripts
  @stack('scripts')
</body>
</html>