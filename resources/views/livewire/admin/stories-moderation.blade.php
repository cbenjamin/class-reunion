<div class="max-w-6xl mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-6">Stories Moderation</h1>

  <div class="grid md:grid-cols-2 gap-6">
    {{-- Pending (approve/reject) --}}
    <section>
      <h2 class="font-semibold mb-2">Pending</h2>
      <div class="space-y-3">
        @forelse($pending as $s)
          <div class="bg-white rounded-xl shadow p-4" wire:key="story-p-{{ $s->id }}">
            <div class="text-sm text-gray-800">“{{ $s->memory }}”</div>
            <div class="mt-2 text-xs text-gray-500">
              by {{ $s->anonymous ? 'Anonymous' : ($s->user?->name ?? 'Classmate') }}
            </div>

            <div class="mt-3 flex items-center gap-2">
              <button
                type="button"
                wire:click="approve({{ $s->id }})"
                wire:loading.attr="disabled"
                class="px-3 py-1 rounded bg-green-600 text-white text-xs disabled:opacity-50">
                Approve
              </button>

              <button
                type="button"
                onclick="if(!confirm('Reject this story?')){ return false; }"
                wire:click="reject({{ $s->id }})"
                wire:loading.attr="disabled"
                class="px-3 py-1 rounded bg-red-600 text-white text-xs disabled:opacity-50">
                Reject
              </button>
            </div>
          </div>
        @empty
          <p class="text-sm text-gray-500">No pending stories.</p>
        @endforelse
      </div>
    </section>

    {{-- Approved (feature/unfeature/remove) --}}
    <section>
      <h2 class="font-semibold mb-2">Approved</h2>
      <div class="space-y-3">
        @forelse($approved as $s)
          <div class="bg-white rounded-xl shadow p-4" wire:key="story-a-{{ $s->id }}">
            <div class="text-sm text-gray-800">“{{ $s->memory }}”</div>
            <div class="mt-2 text-xs text-gray-500">
              by {{ $s->anonymous ? 'Anonymous' : ($s->user?->name ?? 'Classmate') }}
            </div>

            <div class="mt-3 flex items-center justify-between">
              <div class="flex items-center gap-2">
                <button
                  type="button"
                  wire:click="{{ $s->is_featured ? 'unfeature' : 'feature' }}({{ $s->id }})"
                  wire:loading.attr="disabled"
                  class="px-3 py-1 rounded text-xs disabled:opacity-50 {{ $s->is_featured ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-800' }}">
                  {{ $s->is_featured ? 'Unfeature' : 'Feature' }}
                </button>

                <button
                  type="button"
                  onclick="if(!confirm('Remove this story?')){ return false; }"
                  wire:click="reject({{ $s->id }})"
                  wire:loading.attr="disabled"
                  class="px-3 py-1 rounded bg-gray-200 text-gray-800 text-xs disabled:opacity-50">
                  Remove
                </button>
              </div>

              <span class="text-[10px] px-2 py-0.5 rounded-full {{ $s->is_featured ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700' }}">
                {{ $s->is_featured ? 'Featured' : '—' }}
              </span>
            </div>
          </div>
        @empty
          <p class="text-sm text-gray-500">No approved stories yet.</p>
        @endforelse
      </div>
    </section>
  </div>
</div>