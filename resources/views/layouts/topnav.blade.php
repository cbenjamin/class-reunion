@props(['variant' => 'solid'])

@php $isTransparent = $variant === 'transparent'; @endphp

<nav class="{{ $isTransparent ? 'bg-transparent absolute inset-x-0 top-0 z-50' : 'bg-white border-b sticky top-0 z-50' }}">
  <div class="mx-auto max-w-7xl px-4">
    <div class="h-14 flex items-center justify-between">
      <div class="flex items-center gap-3">
        {{-- Hamburger shows on desktop & mobile, only when logged in --}}
        @auth
          <button @click="toggle()"
            class="inline-flex items-center justify-center h-9 w-9 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300"
            aria-label="Open navigation"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-800" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/>
            </svg>
          </button>
        @endauth

        <a href="{{ route('home') }}" class="flex items-center gap-2" aria-label="{{ config('app.name') }}">
          <img src="{{ asset('images/logo.svg') }}" alt="{{ config('app.name') }}" class="h-10 w-auto">
        </a>
      </div>

      <div class="flex items-center gap-3">
        @guest
          <a href="{{ route('invite.create') }}" class="text-sm text-gray-700 hover:text-gray-900">Request Invite</a>
          <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">Log in</a>
        @endguest

        @auth
          {{-- Compact user menu (keep account actions here) --}}
          <div x-data="{open:false}" class="relative">
            <button @click="open=!open" class="text-sm text-gray-700 hover:text-gray-900">
              {{ \Illuminate\Support\Str::of(auth()->user()->name)->words(2, '') }}
            </button>
            <div
              x-show="open" x-transition @click.outside="open=false"
              class="absolute right-0 mt-2 w-44 bg-white border rounded-md shadow z-50"
            >
              <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm hover:bg-gray-50">Profile</a>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50">Log out</button>
              </form>
            </div>
          </div>
        @endauth
      </div>
    </div>
  </div>

  @if($isTransparent)
    <div x-data="{y:0}" x-init="window.addEventListener('scroll',()=>y=window.scrollY)"
         :class="y>10?'backdrop-blur bg-white/40 border-b':'border-b-0'"
         class="h-px"></div>
  @endif
</nav>