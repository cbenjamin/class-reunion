<div class="mt-2 select-none">
  <div class="flex flex-wrap items-center gap-2">
    @foreach($emoji as $type => $icon)
      @php $isMine = $mine === $type; $count = $counts[$type] ?? 0; @endphp
      <button type="button"
              wire:click="react('{{ $type }}')"
              class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs
                     border transition
                     {{ $isMine ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-200 hover:border-gray-300' }}">
        <span aria-hidden="true">{{ $icon }}</span>
        @if($count > 0)
          <span>{{ $count }}</span>
        @endif
      </button>
    @endforeach

    @guest
      <a href="{{ route('login') }}" class="ml-1 text-[11px] text-gray-500 hover:text-gray-700">Log in to react</a>
    @endguest
  </div>
</div>