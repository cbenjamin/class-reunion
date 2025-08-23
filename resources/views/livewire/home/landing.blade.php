@php
  // Build a lightweight array Alpine can use for the modal
  $items = $photos->map(fn($p) => [
    'url' => Storage::disk($p->disk)->url($p->path),
    'caption' => $p->caption,
  ])->values();
@endphp

<div class="relative" x-data="landingPage(@js($items))">
  {{-- HERO (parallax) --}}
  <section class="relative h-[70vh] min-h-[520px] overflow-hidden">
    <div class="absolute inset-0" x-ref="hero" :style="heroStyle">
      @if($heroUrl)
        <img src="{{ $heroUrl }}" alt="Reunion hero"
             class="h-full w-full object-cover will-change-transform select-none pointer-events-none" loading="eager">
      @else
        <div class="h-full w-full bg-gradient-to-br from-indigo-600 via-purple-600 to-fuchsia-600"></div>
      @endif
      <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-black/10"></div>
    </div>

    <div class="relative z-10 h-full">
      <div class="mx-auto max-w-6xl h-full px-5 flex items-center">
        <div class="text-white">
          <p class="text-xs uppercase tracking-widest/relaxed opacity-90">Class Reunion</p>
          <h1 class="mt-1 text-4xl md:text-6xl font-bold leading-tight">{{ $event['name'] ?? config('app.name') }}</h1>
          <p class="mt-3 text-lg md:text-xl opacity-90">
            {{ $event['date'] ?? 'TBD' }} • {{ $event['time'] ?? '' }}
          </p>
          @if(($event['venue'] ?? null) || ($event['address'] ?? null))
          <p class="mt-1 text-sm md:text-base opacity-90">
            {{ $event['venue'] ?? '' }} @if(($event['venue'] ?? null) && ($event['address'] ?? null)) • @endif {{ $event['address'] ?? '' }}
          </p>
          @endif

          <div class="mt-6 flex gap-3">
            <a href="{{ route('invite.create') }}" class="inline-flex items-center rounded-lg bg-white/95 text-gray-900 px-5 py-2.5 text-sm font-medium hover:bg-white">
              Request Invitation
            </a>
            @guest
              <a href="{{ route('login') }}" class="inline-flex items-center rounded-lg border border-white/60 text-white px-5 py-2.5 text-sm font-medium hover:bg-white/10">
                Log In
              </a>
            @endguest
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- INFO STRIP --}}
  <section class="relative">
    <div class="absolute inset-0 -z-10" x-ref="band" :style="bandStyle">
      <div class="h-full w-full bg-[radial-gradient(circle_at_20%_20%,rgba(99,102,241,0.15),transparent_40%),radial-gradient(circle_at_80%_0%,rgba(236,72,153,0.15),transparent_40%)]"></div>
    </div>

    <div class="mx-auto max-w-6xl px-5 py-10 md:py-14">
      <div class="grid md:grid-cols-3 gap-6">
        <div class="bg-white shadow rounded-xl p-6">
          <h3 class="font-semibold mb-2">When</h3>
          <p class="text-sm text-gray-700">{{ $event['date'] ?? 'TBD' }}</p>
          <p class="text-sm text-gray-700">{{ $event['time'] ?? '' }}</p>
        </div>
        <div class="bg-white shadow rounded-xl p-6">
          <h3 class="font-semibold mb-2">Where</h3>
          <p class="text-sm text-gray-700">{{ $event['venue'] ?? 'TBD' }}</p>
          <p class="text-sm text-gray-700">{{ $event['address'] ?? '' }}</p>
        </div>
        <div class="bg-white shadow rounded-xl p-6">
          <h3 class="font-semibold mb-2">What to know</h3>
          <p class="text-sm text-gray-700">{{ $event['notes'] ?? 'More details coming soon.' }}</p>
        </div>
      </div>
    </div>
  </section>

  {{-- PHOTO COLLAGE (approved) --}}
  <section class="mx-auto max-w-6xl px-5 py-12">
    <div class="flex items-end justify-between mb-6">
      <h2 class="text-xl md:text-2xl font-semibold">Photo Collage</h2>
      <p class="text-sm text-gray-500">Approved photos from classmates</p>
    </div>

    <div wire:poll.30s="refreshPhotos"
         class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-4 [column-fill:_balance]">
      @foreach($photos as $i => $p)
        @php $url = Storage::disk($p->disk)->url($p->path); @endphp
        <figure class="break-inside-avoid mb-4">
          <button type="button"
                  class="block w-full text-left"
                  @click="openAt({{ $i }})"
                  aria-label="Open photo">
            <img src="{{ $url }}" alt="{{ $p->caption ?? 'Reunion photo' }}"
                 class="w-full rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300"
                 loading="lazy">
          </button>
          @if($p->caption)
            <figcaption class="mt-2 text-xs text-gray-600">{{ $p->caption }}</figcaption>
          @endif
        </figure>
      @endforeach
      @if($photos->isEmpty())
        <div class="text-gray-600 text-sm">No approved photos yet. Be the first to upload!</div>
      @endif
    </div>

    <div class="mt-8 text-center">
      @auth
        <a href="{{ route('photos.index') }}" class="inline-flex items-center rounded-lg bg-indigo-600 text-white px-5 py-2.5 text-sm font-medium hover:bg-indigo-700">
          Upload Your Photos
        </a>
      @else
        <a href="{{ route('invite.create') }}" class="inline-flex items-center rounded-lg bg-indigo-600 text-white px-5 py-2.5 text-sm font-medium hover:bg-indigo-700">
          Request Invite to Upload
        </a>
      @endauth
    </div>
  </section>

  {{-- FOOTER --}}
  <footer class="py-10 text-center text-xs text-gray-500">
    © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
  </footer>

  {{-- MODAL (Alpine) --}}
  <div x-show="open" x-transition.opacity
       @keydown.escape.window="close()"
       class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4"
       role="dialog" aria-modal="true">
    <button class="absolute inset-0 w-full h-full cursor-zoom-out" @click="close()" aria-label="Close"></button>

    <div class="relative z-10 max-w-6xl w-full">
      <div class="relative bg-transparent">
        <img :src="current?.url" :alt="current?.caption ?? 'Reunion photo'"
             class="mx-auto max-h-[80vh] w-auto object-contain rounded-lg shadow-2xl" />

        <!-- Caption -->
        <div class="mt-3 text-center text-sm text-gray-200" x-text="current?.caption || ''"></div>

        <!-- Controls -->
        <button @click="close()"
                class="absolute -top-3 -right-3 bg-white/90 hover:bg-white text-gray-900 rounded-full p-2 shadow"
                aria-label="Close">
          ✕
        </button>
        <button @click="prev()"
                class="absolute top-1/2 -translate-y-1/2 left-0 ml-2 bg-white/90 hover:bg-white text-gray-900 rounded-full p-2 shadow"
                aria-label="Previous">
          ‹
        </button>
        <button @click="next()"
                class="absolute top-1/2 -translate-y-1/2 right-0 mr-2 bg_white/90 hover:bg-white text-gray-900 rounded-full p-2 shadow"
                aria-label="Next">
          ›
        </button>
      </div>
    </div>
  </div>

  {{-- Minimal parallax + gallery helper --}}
  <script>
    function landingPage(items){
      return {
        // Parallax
        heroY: 0, bandY: 0,
        get heroStyle(){ return `transform: translate3d(0, ${this.heroY}px, 0)`; },
        get bandStyle(){ return `transform: translate3d(0, ${this.bandY}px, 0)`; },
        onScroll(){ const y = window.scrollY || 0; this.heroY = y * 0.25; this.bandY = y * 0.10; },

        // Gallery
        items: items || [],
        open: false,
        index: 0,
        get current(){ return this.items[this.index] ?? null; },
        openAt(i){ if (!this.items.length) return; this.index = i; this.open = true; document.documentElement.classList.add('overflow-hidden'); },
        close(){ this.open = false; document.documentElement.classList.remove('overflow-hidden'); },
        next(){ if (!this.items.length) return; this.index = (this.index + 1) % this.items.length; },
        prev(){ if (!this.items.length) return; this.index = (this.index - 1 + this.items.length) % this.items.length; },

        init(){ this.onScroll(); window.addEventListener('scroll', () => this.onScroll(), { passive: true }); }
      }
    }
  </script>
</div>