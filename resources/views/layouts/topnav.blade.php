@props([
  // 'transparent' on the homepage, 'solid' elsewhere
  'variant' => 'solid',
])

@php
  $isTransparent = $variant === 'transparent';
  $linkClass     = $isTransparent ? 'text-white hover:text-white/90' : 'text-gray-700 hover:text-gray-900';
  $hoverBtnClass = $isTransparent ? 'hover:bg-white/10' : 'hover:bg-gray-100';
  $iconClass     = $isTransparent ? 'text-white' : 'text-gray-700';
@endphp

<nav class="{{ $isTransparent ? 'bg-transparent absolute inset-x-0 top-0 z-50' : 'bg-white border-b sticky top-0 z-50' }}">
  <div class="mx-auto max-w-7xl px-4">
    <div class="h-14 flex items-center justify-between">

      {{-- Left: hamburger (mobile, auth) + logo --}}
      <div class="flex items-center gap-2">
        @auth
          <button
            class="lg:hidden -ml-2 p-2 rounded-md {{ $hoverBtnClass }}"
            @click="navOpen = true"
            aria-label="Open menu"
            aria-controls="mobile-drawer"
            aria-expanded="false"
          >
            {{-- Hamburger icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        @endauth

        <a href="{{ route('home') }}" class="flex items-center gap-2" aria-label="{{ config('app.name') }}">
          {{-- Swap to your logo as desired --}}
          <img src="{{ asset('images/logo.svg') }}" alt="{{ config('app.name') }}" class="h-10 w-auto">
        </a>
      </div>

      {{-- Right: guest links or user menu --}}
      <div class="flex items-center gap-3">
        @guest
          <a href="{{ route('invite.create') }}" class="text-sm {{ $linkClass }}">Request Invite</a>
          <a href="{{ route('login') }}" class="text-sm {{ $linkClass }}">Log in</a>
        @endguest

        @auth
          <div x-data="{ open:false }" class="relative">
            <button @click="open=!open" class="text-sm {{ $linkClass }}" aria-haspopup="menu" :aria-expanded="open ? 'true' : 'false'">
              {{ Str::of(auth()->user()->name)->words(2, '') }}
            </button>
            <div
              x-show="open" x-transition
              @click.outside="open=false"
              class="absolute right-0 mt-2 w-44 bg-white border rounded-md shadow-lg overflow-hidden"
              role="menu" aria-label="User menu"
            >
              <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50" role="menuitem">Profile</a>
              <form method="POST" action="{{ route('logout') }}" role="none">
                @csrf
                <button class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50" role="menuitem">Log out</button>
              </form>
            </div>
          </div>
        @endauth
      </div>
    </div>
  </div>

  {{-- Optional translucent backdrop for transparent variant when scrolled --}}
  @if($isTransparent)
    <div x-data="{ y: 0 }" x-init="window.addEventListener('scroll', () => y = window.scrollY)"
         :class="y>10 ? 'backdrop-blur bg-white/40 border-b' : 'border-b-0'"
         class="h-px"></div>
  @endif
</nav>