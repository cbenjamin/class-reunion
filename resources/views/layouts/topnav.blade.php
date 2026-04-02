@props([
  // 'transparent' on the homepage, 'solid' elsewhere
  'variant' => 'solid',
])

@php
  $isTransparent = $variant === 'transparent';
@endphp

<nav class="{{ $isTransparent ? 'bg-transparent absolute inset-x-0 top-0 z-50' : 'bg-slate-900 sticky top-0 z-50' }}">
  <div class="mx-auto max-w-7xl px-4">
    <div class="h-14 flex items-center justify-between">
      <div class="flex items-center gap-3">

      @auth
      @php $mode = $hamburgerMode ?? 'mobile'; @endphp
        {{-- Show hamburger only on mobile (sidebar handles md+) --}}
        <button @click="drawer=true"
                class="inline-flex md:hidden items-center justify-center h-10 w-10 rounded-md hover:bg-white/10"
                aria-label="Open navigation">
          <i class="fa-solid fa-bars text-white"></i>
        </button>
      @endauth
        <a href="{{ route('home') }}" class="flex items-center gap-2" aria-label="{{ config('app.name') }}">
          <img src="{{ asset('images/logo.svg') }}" alt="{{ config('app.name') }}" class="h-10 w-auto brightness-0 invert">
        </a>
      </div>

      <div class="flex items-center gap-3">
        @guest
          <a href="{{ route('invite.create') }}" class="text-sm text-slate-300 hover:text-white transition">Request Invite</a>
          <a href="{{ route('login') }}" class="text-sm text-slate-300 hover:text-white transition">Log in</a>
        @endguest

        @auth
          {{-- Compact user menu --}}
          <div x-data="{open:false}" class="relative">
            <button @click="open=!open" class="flex items-center gap-1.5 text-sm text-slate-300 hover:text-white transition">
              {{ Str::of(auth()->user()->name)->words(2, '') }}
              <i class="fa-solid fa-chevron-down text-[10px] text-slate-500 transition-transform duration-150" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" @click.outside="open=false" x-transition
                 class="absolute right-0 mt-2 w-44 bg-slate-800 border border-white/10 rounded-lg shadow-xl">
              <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-slate-300 hover:bg-white/5 hover:text-white rounded-t-lg">Profile</a>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-3 py-2 text-sm text-slate-300 hover:bg-white/5 hover:text-white rounded-b-lg">Log out</button>
              </form>
            </div>
          </div>
        @endauth
      </div>
    </div>
  </div>

  {{-- Optional translucent backdrop for transparent variant when scrolled --}}
  @if($isTransparent)
    <div x-data="{y:0}" x-init="window.addEventListener('scroll',()=>y=window.scrollY)"
         :class="y>10?'backdrop-blur bg-white/40 border-b':'border-b-0'"
         class="h-px"></div>
  @endif
</nav>
