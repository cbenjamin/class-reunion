<div class="max-w-6xl mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-6">Ideas Moderation</h1>

  @if(session('status'))
    <div class="mb-4 rounded bg-green-50 text-green-800 px-4 py-3 text-sm">{{ session('status') }}</div>
  @endif

  <div class="grid md:grid-cols-3 gap-6">
    {{-- Pending --}}
    <section>
      <h2 class="font-semibold mb-2">Pending</h2>
      <div class="space-y-3">
        @forelse($pending as $i)
          <article class="bg-white rounded-xl shadow p-4" wire:key="p-{{ $i->id }}">
            <div class="font-semibold">{{ $i->title }}</div>
            <div class="text-xs text-gray-600">
              @if(!$i->anonymous)
                {{ $i->user_name ?? 'Classmate' }} — {{ $i->user_email }}
              @else
                Anonymous
              @endif
            </div>
            @if($i->category)
              <div class="mt-1 text-[11px] px-2 py-0.5 inline-block rounded-full bg-gray-100 text-gray-700">
                {{ $i->category }}
              </div>
            @endif
            <p class="mt-2 text-sm text-gray-800">{{ Str::limit($i->details, 220) }}</p>
            <div class="mt-3 flex items-center gap-2">
              <button wire:click="approve({{ $i->id }})" class="px-3 py-1.5 rounded bg-green-600 text-white text-xs">Approve</button>
              <button onclick="if(!confirm('Reject this idea?')) return false;" wire:click="reject({{ $i->id }})" class="px-3 py-1.5 rounded bg-red-600 text-white text-xs">Reject</button>
            </div>
          </article>
        @empty
          <p class="text-sm text-gray-500">No pending ideas.</p>
        @endforelse
      </div>
    </section>

    {{-- Approved --}}
    <section>
      <h2 class="font-semibold mb-2">Approved</h2>
      <div class="space-y-3">
        @forelse($approved as $i)
          <article class="bg-white rounded-xl shadow p-4" wire:key="a-{{ $i->id }}">
            <div class="font-semibold">{{ $i->title }}</div>
            @if($i->category)
              <div class="mt-1 text-[11px] px-2 py-0.5 inline-block rounded-full bg-gray-100 text-gray-700">
                {{ $i->category }}
              </div>
            @endif
            <p class="mt-2 text-sm text-gray-800">{{ Str::limit($i->details, 220) }}</p>
            <div class="mt-3 flex items-center gap-2">
              <button wire:click="reject({{ $i->id }})" class="px-3 py-1.5 rounded bg-gray-200 text-gray-800 text-xs">Move to Rejected</button>
              <button onclick="if(!confirm('Delete this idea?')) return false;" wire:click="delete({{ $i->id }})" class="px-3 py-1.5 rounded bg-gray-200 text-gray-800 text-xs">Delete</button>
            </div>
          </article>
        @empty
          <p class="text-sm text-gray-500">No approved ideas yet.</p>
        @endforelse
      </div>
    </section>

    {{-- Rejected --}}
    <section>
      <h2 class="font-semibold mb-2">Rejected</h2>
      <div class="space-y-3">
        @forelse($rejected as $i)
          <article class="bg-white rounded-xl shadow p-4" wire:key="r-{{ $i->id }}">
            <div class="font-semibold">{{ $i->title }}</div>
            <p class="mt-2 text-sm text-gray-800">{{ Str::limit($i->details, 220) }}</p>
            <div class="mt-3 flex items-center gap-2">
              <button wire:click="approve({{ $i->id }})" class="px-3 py-1.5 rounded bg-green-600 text-white text-xs">Move to Approved</button>
              <button onclick="if(!confirm('Delete this idea?')) return false;" wire:click="delete({{ $i->id }})" class="px-3 py-1.5 rounded bg-gray-200 text-gray-800 text-xs">Delete</button>
            </div>
          </article>
        @empty
          <p class="text-sm text-gray-500">No rejected ideas.</p>
        @endforelse
      </div>
    </section>
  </div>
</div>