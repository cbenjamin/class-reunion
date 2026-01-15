<div class="mx-auto max-w-6xl px-5 py-10">
  <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-semibold">In Memoriam</h1>
      <p class="text-sm text-gray-600">With love and remembrance of our departed classmates.</p>
    </div>
    <div class="flex items-center gap-2">
      <input type="text" wire:model.debounce.300ms="q" class="field w-64" placeholder="Search name or year">
      @auth
        <a href="{{ route('memorials.submit') }}" class="btn btn-secondary">Submit a Memorial</a>
      @endauth
    </div>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
    @foreach($memorials as $m)
      @php
        $url = $m->photo_path ? Storage::disk($m->disk)->url($m->photo_path) : null;
      @endphp
      <article class="rounded-2xl overflow-hidden bg-white shadow ring-1 ring-black/5">
        <div class="h-40 bg-gray-100 relative">
          @if($url)
            <img src="{{ $url }}" alt="{{ $m->classmate_name }}" class="absolute inset-0 w-full h-full object-cover">
          @else
            <div class="absolute inset-0 bg-gradient-to-br from-black via-red-700 to-black opacity-70"></div>
            <div class="absolute inset-0 flex items-center justify-center text-white text-xl font-semibold">
              {{ \Illuminate\Support\Str::of($m->classmate_name)->substr(0,1) }}
            </div>
          @endif
          @if($m->is_featured)
            <span class="absolute top-2 left-2 text-[11px] px-2 py-0.5 rounded bg-amber-500 text-white">Featured</span>
          @endif
        </div>

        <div class="p-4">
          <h3 class="font-semibold">{{ $m->classmate_name }}
            @if($m->graduation_year)
              <span class="text-gray-500 font-normal">• {{ $m->graduation_year }}</span>
            @endif
          </h3>

          @if($m->bio)
            <p class="text-sm text-gray-700 mt-2">{{ \Illuminate\Support\Str::limit($m->bio, 220) }}</p>
          @endif

          <div class="mt-3 flex items-center justify-between text-xs text-gray-500">
            @if($m->relationship)
              <span>Submitted by {{ $m->submitter_name ?: 'Classmate' }} ({{ $m->relationship }})</span>
            @elseif($m->submitter_name)
              <span>Submitted by {{ $m->submitter_name }}</span>
            @else
              <span>&nbsp;</span>
            @endif

            @if($m->obituary_url)
              <a href="{{ $m->obituary_url }}" class="underline hover:no-underline" target="_blank" rel="noopener">Obituary</a>
            @endif
          </div>
        </div>
      </article>
    @endforeach
  </div>

  <div class="mt-6">
    {{ $memorials->links() }}
  </div>
</div>