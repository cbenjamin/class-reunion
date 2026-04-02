@props([
  'title',
  'description' => null,
])

<div class="bg-zinc-800 border-b border-white/5">
  <div class="mx-auto max-w-6xl px-5 py-5 flex items-center justify-between gap-4">
    <div>
      <h1 class="text-lg font-semibold text-white">{{ $title }}</h1>
      @if($description)
        <p class="text-sm text-slate-400 mt-0.5">{{ $description }}</p>
      @endif
    </div>
    @if(isset($actions))
      <div class="flex items-center gap-3 shrink-0">
        {{ $actions }}
      </div>
    @endif
  </div>
</div>
