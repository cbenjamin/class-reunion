@props([
  // 'transparent' on the homepage, 'solid' elsewhere
  'variant' => 'solid',
])

@php
  $isTransparent = $variant === 'transparent';
@endphp

<nav class="{{ $isTransparent ? 'bg-transparent absolute inset-x-0 top-0 z-50' : 'bg-white border-b sticky top-0 z-50' }}">
  <div class="mx-auto max-w-7xl px-4">
    <div class="h-14 flex items-center justify-between">
      <a href="{{ route('home') }}" class="flex items-center gap-2" aria-label="{{ config('app.name') }}">
        {{-- Swap to your logo as desired --}}
        <img src="{{ asset('images/logo.svg') }}" alt="{{ config('app.name') }}" class="h-10 w-auto">
      </a>

      <!--<div class="flex items-center gap-3">
        @guest
          <a href="{{ route('invite.create') }}" class="text-sm text-gray-700 hover:text-gray-900">Request Invite</a>
          <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">Log in</a>
        @endguest

        @auth
          {{-- Compact user menu; keep actions here, not page links --}}
          <div x-data="{open:false}" class="relative">
            <button @click="open=!open" class="text-sm text-gray-700 hover:text-gray-900">
              {{ Str::of(auth()->user()->name)->words(2, '') }}
            </button>
            <div x-show="open" @click.outside="open=false" x-transition
                 class="absolute right-0 mt-2 w-40 bg-white border rounded-md shadow">
              <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm hover:bg-gray-50">Profile</a>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50">Log out</button>
              </form>
            </div>
          </div>
        @endauth
      </div>-->
    </div>
  </div>

  {{-- Optional translucent backdrop for transparent variant when scrolled --}}
  @if($isTransparent)
    <div x-data="{y:0}" x-init="window.addEventListener('scroll',()=>y=window.scrollY)"
         :class="y>10?'backdrop-blur bg-white/40 border-b':'border-b-0'"
         class="h-px"></div>
  @endif
</nav>