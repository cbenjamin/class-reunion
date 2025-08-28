
  <div class="max-w-4xl mx-auto px-4 py-10 space-y-8">
    @if(session('status'))
      <div class="rounded bg-green-50 text-green-800 px-4 py-3 text-sm">{{ session('status') }}</div>
    @endif

    <div class="rounded-xl border p-4">
      <h2 class="font-semibold mb-3">Upload a Memory</h2>
      <form wire:submit.prevent="save" class="space-y-4">
        <input type="file" wire:model="photo" accept="image/*" class="block">
        @error('photo')<p class="text-sm text-red-600">{{ $message }}</p>@enderror

        <div wire:loading wire:target="photo" class="text-xs text-gray-500">Uploading…</div>

        <input type="text" wire:model.defer="caption" placeholder="Caption (optional)" class="w-full rounded-md border-gray-300">
        @error('caption')<p class="text-sm text-red-600">{{ $message }}</p>@enderror

        <button class="rounded-lg bg-indigo-600 text-white px-4 py-2 hover:bg-indigo-700">Submit</button>
      </form>
      <p class="text-xs text-gray-500 mt-2">Memories go to moderation first. Approved memories will appear publicly later and visible to everyone.</p>
    </div>

    <div>
      <div class="flex items-center justify-between mb-3">
        <h2 class="font-semibold">My Memories</h2>
        @if($pendingCount)
          <span class="text-xs text-gray-500">Pending in queue: {{ $pendingCount }}</span>
        @endif
      </div>
      <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
          @forelse($myPhotos as $p)
            <figure class="rounded-xl overflow-hidden border">
              <img src="{{ Storage::disk($p->disk)->url($p->path) }}" alt="" class="w-full h-48 object-cover">
              <figcaption class="p-2 text-xs text-gray-600">
                <div class="flex items-center justify-between gap-2">
                  <span class="truncate">{{ $p->caption }}</span>
                  <span class="px-2 py-0.5 rounded-full text-[10px]
                    {{ $p->status==='approved' ? 'bg-green-100 text-green-800'
                        : ($p->status==='pending' ? 'bg-yellow-100 text-yellow-800'
                        : 'bg-red-100 text-red-800') }}">
                    {{ ucfirst($p->status) }}
                  </span>
                </div>
                <div class="mt-2 flex items-center justify-end">
                  <button
                    class="text-red-600 hover:text-red-700 text-[12px]"
                    onclick="if(!confirm('Remove this photo? This cannot be undone.')){ return false; }"
                    wire:click="delete({{ $p->id }})">
                    Delete
                  </button>
                </div>
              </figcaption>
            </figure>
          @empty
            <p class="text-sm text-gray-600">No uploads yet.</p>
          @endforelse
        </div>
    </div>
  </div>
