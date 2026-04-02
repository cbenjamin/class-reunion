
<x-page-header title="My Photos" description="Upload your memories — approved photos appear on the homepage and at the reunion." />

<div class="max-w-4xl mx-auto px-4 py-10 space-y-8">
  @if(session('status'))
    <div class="rounded-lg bg-green-50 text-green-800 px-4 py-3 text-sm">{{ session('status') }}</div>
  @endif

  {{-- Upload card --}}
  <div class="bg-white rounded-xl shadow ring-1 ring-black/5 p-6">
    <h2 class="font-semibold mb-4">Upload a Photo</h2>
    <form wire:submit.prevent="save" class="space-y-5">

      {{-- Drop zone --}}
      <div x-data="{ dragging: false }">
        @if($photo)
          {{-- Live preview --}}
          <div class="relative rounded-xl overflow-hidden">
            <img src="{{ $photo->temporaryUrl() }}" class="w-full max-h-64 object-cover" alt="Preview">
            <div class="absolute inset-0 bg-black/30 flex items-end p-3">
              <span class="text-xs text-white/80 bg-black/40 rounded px-2 py-0.5">Preview · click below to change</span>
            </div>
          </div>
          <label for="photoInput"
                 class="mt-3 flex items-center justify-center gap-2 w-full py-2 rounded-lg border border-dashed border-gray-300
                        text-sm text-gray-500 cursor-pointer hover:border-red-400 hover:text-red-600 transition">
            <i class="fa-solid fa-arrows-rotate text-xs"></i> Choose a different photo
            <input id="photoInput" type="file" wire:model="photo" accept="image/*" class="sr-only">
          </label>
        @else
          <label for="photoInput"
                 class="flex flex-col items-center justify-center h-48 rounded-xl border-2 border-dashed
                        cursor-pointer transition"
                 :class="dragging ? 'border-red-500 bg-red-50' : 'border-gray-300 hover:border-red-400 hover:bg-red-50'"
                 @dragover.prevent="dragging=true"
                 @dragleave.prevent="dragging=false"
                 @drop.prevent="dragging=false">
            <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-3"
               :class="dragging ? 'text-red-500' : 'text-gray-400'"></i>
            <span class="text-sm font-medium text-gray-600">Drag &amp; drop or <span class="text-red-700 underline">browse</span></span>
            <span class="text-xs text-gray-400 mt-1">JPG, PNG, WebP · max 10MB</span>
            <input id="photoInput" type="file" wire:model="photo" accept="image/*" class="sr-only">
          </label>
        @endif

        <div wire:loading wire:target="photo" class="flex items-center gap-2 mt-2 text-sm text-gray-500">
          <svg class="animate-spin h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
          </svg>
          Uploading…
        </div>
        @error('photo')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
      </div>

      {{-- Caption --}}
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Caption <span class="text-gray-400 font-normal">(optional)</span></label>
        <input type="text" wire:model.defer="caption" placeholder="Add a caption…"
               class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:ring-red-500 focus:border-red-500">
        @error('caption')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
      </div>

      <div class="flex items-center gap-4">
        <button class="btn btn-primary" wire:loading.attr="disabled" wire:target="save">
          <span wire:loading.remove wire:target="save"><i class="fa-solid fa-upload mr-1.5 text-xs"></i>Submit Photo</span>
          <span wire:loading wire:target="save">Submitting…</span>
        </button>
        <p class="text-xs text-gray-500">Photos are moderated before appearing publicly.</p>
      </div>
    </form>
  </div>

  {{-- My photos --}}
  <div>
    <div class="flex items-center justify-between mb-4">
      <h2 class="font-semibold">My Photos</h2>
      @if($pendingCount)
        <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">{{ $pendingCount }} pending review</span>
      @endif
    </div>

    @if($myPhotos->isEmpty())
      <div class="text-center py-16 text-gray-400">
        <i class="fa-solid fa-images text-4xl mb-3"></i>
        <p class="text-sm">No uploads yet — add your first photo above.</p>
      </div>
    @else
      <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        @foreach($myPhotos as $p)
          <figure class="group rounded-xl overflow-hidden bg-white shadow ring-1 ring-black/5">
            <div class="relative">
              <img src="{{ Storage::disk($p->disk)->url($p->path) }}" alt="{{ $p->caption }}" class="w-full h-48 object-cover">
              <span class="absolute top-2 right-2 px-2 py-0.5 rounded-full text-[10px] font-semibold
                {{ $p->status==='approved' ? 'bg-green-500 text-white'
                    : ($p->status==='pending' ? 'bg-yellow-400 text-yellow-900'
                    : 'bg-red-500 text-white') }}">
                {{ ucfirst($p->status) }}
              </span>
            </div>
            <figcaption class="px-3 py-2 text-xs text-gray-600 flex items-center justify-between gap-2">
              <span class="truncate">{{ $p->caption ?: '—' }}</span>
              <button
                class="text-red-600 hover:text-red-800 shrink-0"
                wire:click="delete({{ $p->id }})"
                wire:confirm="Remove this photo? This cannot be undone.">
                <i class="fa-solid fa-trash-can"></i>
              </button>
            </figcaption>
          </figure>
        @endforeach
      </div>
    @endif
  </div>
</div>
