
  <div class="max-w-7xl mx-auto px-4 py-10 space-y-8">
    <h1 class="text-2xl font-bold">Memory Moderation</h1>

    <section>
      <h2 class="font-semibold mb-2">Pending</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @forelse($pending as $p)
          <div class="border rounded-xl overflow-hidden">
            <img src="{{ Storage::disk($p->disk)->url($p->path) }}" class="w-full h-40 object-cover" alt="">
            <div class="p-2 text-xs text-gray-600">{{ $p->caption }}</div>
              <div class="p-2 flex gap-2">
                <button wire:click="approve({{ $p->id }})" class="px-2 py-1 rounded bg-green-600 text-white text-xs">Approve</button>
                <button wire:click="reject({{ $p->id }})" class="px-2 py-1 rounded bg-red-600 text-white text-xs">Reject</button>
                <button
                  onclick="if(!confirm('Delete this photo? This cannot be undone.')){ return false; }"
                  wire:click="delete({{ $p->id }})"
                  class="px-2 py-1 rounded bg-gray-200 text-gray-800 text-xs">
                  Delete
                </button>
              </div>
          </div>
        @empty
          <p class="text-sm text-gray-600">No pending photos.</p>
        @endforelse
      </div>
    </section>

    <section>
      <h2 class="font-semibold mb-2 mt-8">Approved</h2>
      <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
        @forelse($approved as $p)
          <div class="border rounded-xl overflow-hidden">
            <img src="{{ Storage::disk($p->disk)->url($p->path) }}" class="w-full h-32 object-cover" alt="">
            <div class="p-2 flex items-center justify-between">
              <div class="flex items-center gap-2">
                <button wire:click="{{ $p->is_featured ? 'unfeature' : 'feature' }}({{ $p->id }})"
                        class="px-2 py-1 rounded bg-indigo-600 text-white text-xs">
                  {{ $p->is_featured ? 'Unfeature' : 'Feature' }}
                </button>
                <button
                  onclick="if(!confirm('Delete this photo? This cannot be undone.')){ return false; }"
                  wire:click="delete({{ $p->id }})"
                  class="px-2 py-1 rounded bg-gray-200 text-gray-800 text-xs">
                  Delete
                </button>
              </div>
              <span class="text-[10px] px-2 py-0.5 rounded-full {{ $p->is_featured ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700' }}">
                {{ $p->is_featured ? 'Featured' : '—' }}
              </span>
            </div>
          </div>
        @empty
          <p class="text-sm text-gray-600">No approved memories yet.</p>
        @endforelse
      </div>
    </section>
  </div>
