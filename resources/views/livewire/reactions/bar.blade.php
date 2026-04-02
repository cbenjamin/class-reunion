<div class="mt-2 select-none">
  <div class="flex items-center gap-1">
    @foreach($emoji as $type => $icon)
      @php $isMine = $mine === $type; $count = $counts[$type] ?? 0; @endphp
      <button type="button"
              wire:click="react('{{ $type }}')"
              title="{{ ucfirst($type) }}"
              class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium
                     border transition-all
                     {{ $isMine
                        ? 'bg-red-700 text-white border-red-700 scale-105'
                        : 'bg-white text-gray-600 border-gray-200 hover:border-red-300 hover:text-red-700' }}">
        <span aria-hidden="true">{{ $icon }}</span>
        @if($count > 0)
          <span class="tabular-nums">{{ $count }}</span>
        @endif
      </button>
    @endforeach

    @guest
      <a href="{{ route('login') }}" class="ml-1 text-[10px] text-gray-400 hover:text-gray-600 whitespace-nowrap">Log in to react</a>
    @endguest
  </div>
</div>
