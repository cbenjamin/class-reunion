@php
  $bg = [
    'from-pink-500/90 to-rose-500/90',
    'from-indigo-500/90 to-blue-500/90',
    'from-emerald-500/90 to-teal-500/90',
    'from-amber-500/90 to-orange-500/90',
    'from-fuchsia-500/90 to-purple-500/90',
  ];
@endphp

<div class="mt-12 mx-auto max-w-6xl px-4 md:px-6">
  <div class="flex items-end justify-between mb-4">
    <h2 class="text-xl md:text-2xl font-semibold">Memory Book</h2>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($stories as $i => $s)

    <div class="relative" style="perspective: 1000px;">
  <article
    x-data="tiltCard()"
    @mousemove="onMove($event)"
    @mouseenter="onEnter()"
    @mouseleave="onLeave()"
    :style="style"
    class="group rounded-2xl overflow-hidden shadow ring-1 ring-black/5
           backdrop-blur bg-gradient-to-br {{ $bg[$i % count($bg)] }} relative
           transition-transform duration-300 ease-out transform-gpu will-change-transform"
  >
        <div class="p-4 sm:p-5 text-white">
          <p class="text-sm opacity-90">“{{ Str::limit($s->memory, 260) }}”</p>

          <div class="mt-3 flex flex-wrap gap-2 text-xs">
            @if($s->teacher)
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-white/15">
                👩‍🏫 {{ $s->teacher }}
              </span>
            @endif
            @if($s->song)
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-white/15">
                🎵 {{ $s->song }}
              </span>
            @endif
          </div>

          @if($s->now)
            <p class="mt-3 text-[13px] opacity-90">Now: {{ Str::limit($s->now, 180) }}</p>
          @endif

          <div class="mt-4 flex items-center justify-between text-xs opacity-95">
            <div class="font-medium">
              — {{ $s->anonymous ? 'Anonymous' : Str::of($s->user?->name ?? 'Classmate')->words(2,'') }}
            </div>
            <!--<time datetime="{{ $s->created_at->toDateString() }}">{{ $s->created_at->format('M j') }}</time>-->
          </div>
        </div>

        {{-- subtle tilt on hover --}}
        <div class="absolute inset-0 pointer-events-none transition-transform duration-300 group-hover:rotate-1"></div>

    {{-- subtle tilt highlight --}}
    <div class="absolute inset-0 pointer-events-none transition-opacity duration-300
                opacity-0 group-hover:opacity-100"
         style="background: radial-gradient(600px circle at var(--mx,50%) var(--my,50%), rgba(255,255,255,.12), transparent 40%);">
    </div>
  </article>
</div>

    @empty
      <div class="col-span-full text-sm text-gray-600">No stories yet. Be the first!</div>
    @endforelse
  </div>
      <div class="mt-8 text-center">
      @auth
        <a href="{{ route('stories.new') }}" class="inline-flex items-center rounded-lg bg-indigo-600 text-white px-5 py-2.5 text-sm font-medium hover:bg-indigo-700">
          Share Your Story
        </a>
      @else
        <a href="{{ route('invite.create') }}" class="inline-flex items-center rounded-lg bg-indigo-600 text-white px-5 py-2.5 text-sm font-medium hover:bg-indigo-700">
          Request Invite to Submit Memories
        </a>
      @endauth
    </div>
</div>
<script>
  function tiltCard() {
    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    return {
      style: '',
      onEnter() { if (prefersReduced) return; this.style = 'transform: translateZ(0)'; },
      onMove(e) {
        if (prefersReduced) return;
        const el = e.currentTarget;
        const r = el.getBoundingClientRect();
        const px = (e.clientX - r.left) / r.width;   // 0..1
        const py = (e.clientY - r.top)  / r.height;  // 0..1
        const rx = (py - 0.5) * -20; // tilt X
        const ry = (px - 0.5) *  20; // tilt Y
        el.style.setProperty('--mx', `${Math.round(px*100)}%`);
        el.style.setProperty('--my', `${Math.round(py*100)}%`);
        this.style = `transform:
            perspective(1000px)
            rotateX(${rx}deg)
            rotateY(${ry}deg)
            translateY(-2px)`;
      },
      onLeave() {
        this.style = 'transform: translateY(0)';
      }
    }
  }
</script>