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
<style>[x-cloak]{ display:none !important; }</style>
</head>
<body class="font-sans antialiased bg-gray-100"
      x-data="{ drawer:false }"
      @keydown.window.escape="drawer=false">
  {{-- Top bar (transparent on home, solid elsewhere) --}}
  @include('layouts.topnav', ['variant' => request()->routeIs('home') ? 'transparent' : 'solid'])

  {{-- Off-canvas drawer used by the hamburger --}}
  @include('layouts.drawer')

  <main class="min-h-screen">
    {{ $slot }}
  </main>

  @livewireScripts

  <script>
    function navState(){
      const KEY_PIN = 'nav:pinned';
      const KEY_OPEN = 'nav:open';
      const mq = window.matchMedia('(min-width: 1024px)');

      return {
        drawer: false,
        pinned: false,
        isDesk: mq.matches,

        init(){
          // load persisted state
          this.pinned = localStorage.getItem(KEY_PIN) === '1';
          const persistedOpen = localStorage.getItem(KEY_OPEN) === '1';

          // default behavior
          this.drawer = this.pinned ? true : (this.isDesk ? true : persistedOpen);

          // watch & persist
          this.$watch('pinned', v => {
            localStorage.setItem(KEY_PIN, v ? '1' : '0');
            if (!v && !this.isDesk) this.drawer = false;
          });
          this.$watch('drawer', v => localStorage.setItem(KEY_OPEN, v ? '1' : '0'));

          // respond to viewport changes
          const onChange = e => {
            this.isDesk = e.matches;
            if (this.isDesk) {
              if (!this.drawer && (this.pinned || localStorage.getItem(KEY_OPEN) !== '0')) {
                this.drawer = true;
              }
            } else {
              if (!this.pinned) this.drawer = false;
            }
          };
          mq.addEventListener ? mq.addEventListener('change', onChange) : mq.addListener(onChange);
        },

        toggle(){ this.drawer = !this.drawer; },
        togglePin(){ this.pinned = !this.pinned; },
        closeOnMobile(){ if (!this.isDesk && !this.pinned) this.drawer = false; },
      }
    }
  </script>
    @stack('scripts')
</body>
</html>