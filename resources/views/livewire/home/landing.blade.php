@php $items = []; @endphp
@auth
  @php
    foreach ($photos as $p) {
      $items[] = [
        'url'     => Storage::disk($p->disk)->url($p->path),
        'caption' => $p->caption,
      ];
    }
  @endphp
@endauth

<div class="relative" x-data="landingPage(@js($items))">
  {{-- HERO (parallax) --}}
  <section class="relative h-[70vh] min-h-[520px] overflow-hidden">
    <div class="absolute inset-0" x-ref="hero" :style="heroStyle">
      @auth
        @if($heroUrl)
          <img src="{{ $heroUrl }}" alt="Reunion hero"
               class="h-full w-full object-cover will-change-transform select-none pointer-events-none" loading="eager">
        @else
          <div class="h-full w-full bg-gradient-to-br from-black via-red-700 to-black"></div>
        @endif
      @else
        {{-- Guests see a neutral gradient (no photos) --}}
        <div class="h-full w-full bg-gradient-to-br from-black via-red-700 to-black"></div>
      @endauth
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

          <div class="mt-6 flex flex-wrap gap-3">
            @auth
              <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-lg bg-white/95 text-gray-900 px-5 py-2.5 text-sm font-medium hover:bg-white">
                Go to Dashboard
              </a>
            @else
              <a href="{{ route('invite.create') }}" class="inline-flex items-center rounded-lg bg-white/95 text-gray-900 px-5 py-2.5 text-sm font-medium hover:bg-white">
                Request Invitation
              </a>
              <a href="{{ route('login') }}" class="inline-flex items-center rounded-lg border border-white/60 text-white px-5 py-2.5 text-sm font-medium hover:bg-white/10">
                Log In
              </a>
            @endauth
          </div>
        </div>
      </div>
    </div>
  </section>

{{-- ==================== LOGGED-IN CONTENT ==================== --}}
@auth
  {{-- INFO STRIP (keep as-is) --}}
  <section>
    <div class="absolute inset-0 -z-10" x-ref="band" :style="bandStyle">
      <div class="h-full w-full bg-[radial-gradient(circle_at_20%_20%,rgba(99,102,241,0.15),transparent_40%),radial-gradient(circle_at_80%_0%,rgba(236,72,153,0.15),transparent_40%)]"></div>
    </div>

    <div class="mx-auto max-w-6xl px-5 py-10 md:py-14">
      <div class="grid md:grid-cols-3 gap-6">
        <div class="bg-white shadow rounded-xl p-6">
          <h3 class="font-semibold mb-2">When</h3>
          <p class="text-sm text-gray-700">Date: {{ $event['date'] }}</p>
          <p class="text-sm text-gray-700">Time: {{ $event['time'] }}</p>
        </div>
        <div class="bg-white shadow rounded-xl p-6">
          <h3 class="font-semibold mb-2">Where</h3>
          <p class="text-sm text-gray-700">Venue: {{ $event['venue'] }}</p>
          <p class="text-sm text-gray-700">Address: {{ $event['address'] }}</p>
        </div>
        <div class="bg-white shadow rounded-xl p-6">
          <h3 class="font-semibold mb-2">What to know</h3>
          <p class="text-sm text-gray-700">{!! $event['notes'] !!}</p>
        </div>
      </div>
    </div>
  </section>

  {{-- Unified container with vertical rhythm between bubbles --}}
  <div class="mx-auto max-w-6xl px-5 pb-14 space-y-8 md:space-y-12">

    {{-- ========= PHOTOS (bubble) ========= --}}
    <section id="photos" class="bubble">
      <div class="bubble-inner">
        <div class="flex items-end justify-between mb-6">
          <div>
            <h2 class="bubble-title">Photos</h2>
            <p class="bubble-sub">Approved photos from classmates</p>
          </div>
          <a href="{{ route('photos.index') }}" class="hidden sm:inline-flex items-center rounded-lg bg-indigo-600 text-white px-4 py-2 text-sm font-medium hover:bg-indigo-700">
            Upload Your Photos
          </a>
        </div>

        <div class="masonry masonry-1 sm:masonry-2 lg:masonry-3 xl:masonry-4" wire:poll.30s.keep-alive="refreshPhotos">
          @foreach($photos as $i => $p)
            @php $url = Storage::disk($p->disk)->url($p->path); @endphp
            <figure class="group avoid-column-break mb-4">
              <button type="button" class="block w-full text-left"
                      @click="openAt({{ $i }})" aria-label="Open photo">
                <img src="{{ $url }}"
                     alt="{{ $p->caption ?? 'Reunion photo' }}"
                     class="w-full h-auto rounded-xl shadow-md hover:shadow-lg transition duration-300"
                     loading="lazy">
              </button>
              @if($p->caption)
                <figcaption class="mt-2 text-xs text-gray-600">{{ $p->caption }}</figcaption>
              @endif
             <livewire:reactions.bar :photo="$p->id" :key="'rx-'.$p->id" />
            </figure>
          @endforeach

          @if($photos->isEmpty())
            <div class="text-gray-600 text-sm">No approved photos yet. Be the first to upload!</div>
          @endif
        </div>

        <div class="mt-6 sm:hidden text-center">
          <a href="{{ route('photos.index') }}" class="inline-flex items-center rounded-lg bg-indigo-600 text-white px-4 py-2 text-sm font-medium hover:bg-indigo-700">
            Upload Your Photos
          </a>
        </div>
      </div>
    </section>

    {{-- ========= MEMORY BOOK (bubble) ========= --}}
    <section id="memory-book" class="bubble">
      <div class="bubble-inner">
        <div class="flex items-center justify-between mb-6">
          <h2 class="bubble-title">Memory Book</h2>
          <a href="{{ route('stories.new') }}" class="btn btn-secondary">Share Your Story</a>
        </div>

        <livewire:stories.wall :limit="18" />
      </div>
    </section>

    {{-- ========= WHERE ARE WE NOW? (bubble) ========= --}}
    <section id="where-now" class="bubble">
      <div class="bubble-inner">
        <div class="flex items-center justify-between mb-6">
          <div>
            <h2 class="bubble-title">Where are we now?</h2>
            <p class="bubble-sub">Approximate city-level locations of classmates who opted in.</p>
          </div>
          <a href="{{ route('settings.location') }}" class="btn btn-secondary">Update my location</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
          <div class="lg:col-span-3">
            <div class="rounded-xl overflow-hidden ring-1 ring-black/5" wire:ignore>
              <div id="home-where-map" style="height: 420px;"></div>
            </div>
          </div>

          <aside class="lg:col-span-1">
            <div class="rounded-xl bg-white shadow ring-1 ring-black/5 p-4">
              <h3 class="font-medium">Top cities</h3>
              <ul class="mt-3 space-y-2">
                @forelse($topCities as $c)
                  <li class="flex items-center justify-between text-sm">
                    <span>{{ $c['city'] ?: 'Unknown' }}, {{ $c['state'] }}</span>
                    <span class="text-gray-600">{{ $c['count'] }}</span>
                  </li>
                @empty
                  <li class="text-sm text-gray-500">No data yet.</li>
                @endforelse
              </ul>
              <p class="mt-4 text-xs text-gray-500">Only opt-in classmates are shown.</p>
            </div>
          </aside>
        </div>

        {{-- Leaflet / cluster includes + init (make sure these are only included once per page) --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css"/>
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css"/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
        <script>
          (() => {
            if (window._homeWhereMapInited) return;
            window._homeWhereMapInited = true;

            const data = @json($mapMarkers);
            const el = document.getElementById('home-where-map');
            if (!el) return;

            const map = L.map(el, { worldCopyJump: true });
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              maxZoom: 18, attribution: '&copy; OpenStreetMap',
            }).addTo(map);

            const cluster = L.markerClusterGroup({ showCoverageOnHover: false, spiderfyOnMaxZoom: true });
            const bounds = [];
            (data || []).forEach(p => {
              if (!p.lat || !p.lng) return;
              const m = L.marker([p.lat, p.lng]);
              m.bindPopup(`<div class="text-sm"><div class="font-medium">${p.name}</div><div class="text-gray-600">${p.label}</div></div>`);
              cluster.addLayer(m);
              bounds.push([p.lat, p.lng]);
            });
            map.addLayer(cluster);
            bounds.length ? map.fitBounds(bounds, { padding: [24,24] }) : map.setView([20,0], 2);
          })();
        </script>
      </div>
    </section>

  </div>
@endauth

  {{-- ==================== GUEST CONTENT (NO PHOTOS OR STORIES) ==================== --}}
  @guest
    {{-- Value Props --}}
    <section class="mx-auto max-w-6xl px-5 py-12">
      <div class="grid md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow p-6">
          <div class="text-2xl">📸</div>
          <h3 class="mt-2 font-semibold">Share Your Memories</h3>
          <p class="text-sm text-gray-600 mt-1">Upload photos from school days. The best get featured during the event.</p>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
          <div class="text-2xl">💬</div>
          <h3 class="mt-2 font-semibold">Tell Your Story</h3>
          <p class="text-sm text-gray-600 mt-1">Add a short “Memory Book” entry—favorite teacher, song, what you’re up to now.</p>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
          <div class="text-2xl">🎟️</div>
          <h3 class="mt-2 font-semibold">Easy Registration</h3>
          <p class="text-sm text-gray-600 mt-1">Request an invite and, once approved, you’ll unlock the dashboard with all details.</p>
        </div>
      </div>
    </section>

{{-- How it works + Stats (boxed, side-by-side, with 10px gutters) --}}
<section class="bg-white/60">
  <div class="mx-auto max-w-6xl px-5 py-14">
    {{-- NOTE: -mx-2.5 on the grid + px-2.5 on each column wrapper => 10px gutters --}}
    <div class="grid md:grid-cols-2 gap-12 md:gap-16 items-start -mx-2.5">
      
      {{-- LEFT COLUMN WRAPPER (adds 10px side gutter) --}}
      <div class="px-2.5">
        {{-- How it works (boxed) --}}
        <div class="rounded-2xl md:rounded-3xl bg-white shadow-lg ring-1 ring-black/5">
          <div class="p-8 md:p-10">
            <h2 class="text-2xl font-semibold">How it works</h2>

            <ol class="mt-6 space-y-5">
              <li class="flex items-start gap-3">
                <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full
                             bg-indigo-600 text-white text-sm font-semibold leading-none ring-2 ring-white shadow">1</span>
                <p class="text-sm text-gray-700 leading-6">
                  <strong>Request an invitation</strong> with your name and graduation year.
                </p>
              </li>
              <li class="flex items-start gap-3">
                <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full
                             bg-indigo-600 text-white text-sm font-semibold leading-none ring-2 ring-white shadow">2</span>
                <p class="text-sm text-gray-700 leading-6">
                  <strong>Get approved & set your password</strong> via a secure link emailed to you.
                </p>
              </li>
              <li class="flex items-start gap-3">
                <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full
                             bg-indigo-600 text-white text-sm font-semibold leading-none ring-2 ring-white shadow">3</span>
                <p class="text-sm text-gray-700 leading-6">
                  <strong>Unlock the dashboard</strong> to see event details, upload photos, and add your Memory Book story.
                </p>
              </li>
            </ol>

            <div class="mt-8 flex flex-wrap gap-3">
              <a href="{{ route('invite.create') }}" class="inline-flex items-center rounded-lg bg-indigo-600 text-white px-5 py-2.5 text-sm font-medium hover:bg-indigo-700">
                Request Invitation
              </a>
              <a href="{{ route('login') }}" class="inline-flex items-center rounded-lg border border-gray-300 text-gray-800 px-5 py-2.5 text-sm font-medium hover:bg-gray-50">
                Log In
              </a>
            </div>
          </div>
        </div>
      </div>

      {{-- RIGHT COLUMN WRAPPER (adds 10px side gutter) --}}
      <div class="px-2.5">
        {{-- At a glance (boxed) --}}
        <div class="rounded-2xl md:rounded-3xl bg-white shadow-lg ring-1 ring-black/5">
          <div class="p-8 md:p-10">
            <h2 class="text-2xl font-semibold text-center md:text-left">At a glance</h2>

            <div class="mt-6 grid grid-cols-2 md:grid-cols-3 gap-6">
              <div class="rounded-2xl bg-white shadow p-8 text-center ring-1 ring-gray-100">
                <div class="text-4xl font-semibold tabular-nums leading-none">{{ number_format($stats['classmates']) }}</div>
                <div class="mt-2 text-sm text-gray-500">Approved Classmates</div>
              </div>
              <div class="rounded-2xl bg-white shadow p-8 text-center ring-1 ring-gray-100">
                <div class="text-4xl font-semibold tabular-nums leading-none">{{ number_format($stats['photos']) }}</div>
                <div class="mt-2 text-sm text-gray-500">Photos Shared</div>
              </div>
              <div class="rounded-2xl bg-white shadow p-8 text-center ring-1 ring-gray-100">
                <div class="text-4xl font-semibold tabular-nums leading-none">{{ number_format($stats['stories']) }}</div>
                <div class="mt-2 text-sm text-gray-500">Stories Posted</div>
              </div>
            </div>

            <p class="mt-5 text-xs text-gray-500 text-center md:text-left">Live counts update as classmates join.</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
    {{-- FAQ --}}
    <section class="mx-auto max-w-6xl px-5 pb-14 py-12">
      <h2 class="text-xl md:text-2xl font-semibold">FAQ</h2>
      <div class="mt-4 divide-y rounded-xl border bg-white">
        @php
          $faqs = [
            ['q'=>'Why do I need an invite?','a'=>'We verify classmates to keep the community private and safe. Approval is quick.'],
            ['q'=>'What happens after approval?','a'=>'You’ll set your password, access event details, upload photos, and add your Memory Book story.'],
            ['q'=>'Can I bring a guest?','a'=>'Details will be in the event info after you’re approved.'],
          ];
        @endphp
        @foreach($faqs as $idx => $f)
          <div x-data="{open:false}" class="p-4">
            <button @click="open=!open" class="w-full text-left flex items-center justify-between">
              <span class="font-medium">{{ $f['q'] }}</span>
              <span x-text="open ? '−' : '+'"></span>
            </button>
            <div x-show="open" x-transition class="pt-2 text-sm text-gray-600">{{ $f['a'] }}</div>
          </div>
        @endforeach
      </div>

      <div class="mt-8 text-center">
        <a href="{{ route('invite.create') }}" class="inline-flex items-center rounded-lg bg-indigo-600 text-white px-5 py-2.5 text-sm font-medium hover:bg-indigo-700">
          Request Invitation
        </a>
      </div>
    </section>
  @endguest

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
        <div class="mt-3 text-center text-sm text-gray-200" x-text="current?.caption || ''"></div>
        <button @click="close()" class="absolute -top-3 -right-3 bg-white/90 hover:bg-white text-gray-900 rounded-full p-2 shadow" aria-label="Close">✕</button>
        <button @click="prev()"  class="absolute top-1/2 -translate-y-1/2 left-0 ml-2 bg-white/90 hover:bg-white text-gray-900 rounded-full p-2 shadow" aria-label="Previous">‹</button>
        <button @click="next()"  class="absolute top-1/2 -translate-y-1/2 right-0 mr-2 bg-white/90 hover:bg-white text-gray-900 rounded-full p-2 shadow" aria-label="Next">›</button>
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

        // Gallery (only used for authed users)
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