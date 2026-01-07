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
    <a href="{{ route('stories.new') }}" class="text-sm text-indigo-700 hover:underline">Share yours</a>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($stories as $i => $s)
      <article
        class="group rounded-2xl overflow-hidden shadow ring-1 ring-black/5 backdrop-blur bg-gradient-to-br {{ $bg[$i % count($bg)] }} relative"
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
            <time datetime="{{ $s->created_at->toDateString() }}">{{ $s->created_at->format('M j') }}</time>
          </div>
        </div>

        {{-- subtle tilt on hover --}}
        <div class="absolute inset-0 pointer-events-none transition-transform duration-300 group-hover:rotate-1"></div>
      </article>
    @empty
      <div class="col-span-full text-sm text-gray-600">No stories yet. Be the first!</div>
    @endforelse
  </div>
</div>