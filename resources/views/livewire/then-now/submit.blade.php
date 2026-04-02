<x-page-header title="Submit Then & Now" description="Upload a photo from back in the day alongside one from today. One submission per classmate." />

<div class="max-w-2xl mx-auto px-4 py-10">

    @if(session('status'))
        <div class="mb-6 rounded-lg bg-green-50 text-green-800 px-4 py-3 text-sm">{{ session('status') }}</div>
    @endif

    {{-- Existing submission status --}}
    @if($existing)
        <div class="mb-8 bg-white rounded-xl shadow ring-1 ring-black/5 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-gray-800">Your current submission</span>
                    <span class="ml-2 inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                        {{ $existing->status === 'approved' ? 'bg-green-100 text-green-700' :
                           ($existing->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ ucfirst($existing->status) }}
                    </span>
                </div>
                <button wire:click="delete" wire:confirm="Remove your Then & Now submission?"
                        class="text-xs text-red-600 hover:text-red-800">
                    Remove
                </button>
            </div>
            <div class="grid grid-cols-2">
                <div class="relative">
                    <img src="{{ Storage::disk($existing->then_disk)->url($existing->then_path) }}"
                         class="w-full h-48 object-cover" alt="Then">
                    <span class="absolute bottom-2 left-2 text-[11px] font-semibold text-white bg-black/50 rounded px-1.5 py-0.5">Then</span>
                </div>
                <div class="relative border-l-2 border-white">
                    <img src="{{ Storage::disk($existing->now_disk)->url($existing->now_path) }}"
                         class="w-full h-48 object-cover" alt="Now">
                    <span class="absolute bottom-2 right-2 text-[11px] font-semibold text-white bg-black/50 rounded px-1.5 py-0.5">Now</span>
                </div>
            </div>
            @if($existing->caption)
                <p class="px-5 py-3 text-sm text-gray-600">{{ $existing->caption }}</p>
            @endif
        </div>

        <p class="text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-4 py-3 mb-6">
            Submitting again will replace your current entry and reset its status to pending.
        </p>
    @endif

    {{-- Upload form --}}
    <form wire:submit="save" class="bg-white rounded-xl shadow ring-1 ring-black/5 p-6 space-y-6">

        <div class="grid grid-cols-2 gap-4">

            {{-- THEN --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Then <span class="text-gray-400 font-normal">(old photo)</span></label>

                @if($thenPhoto)
                    <div class="relative rounded-lg overflow-hidden mb-2">
                        <img src="{{ $thenPhoto->temporaryUrl() }}" class="w-full h-44 object-cover" alt="Then preview">
                        <span class="absolute bottom-2 left-2 text-[11px] font-semibold text-white bg-black/50 rounded px-1.5 py-0.5">Then</span>
                    </div>
                @else
                    <label for="thenPhoto"
                           class="flex flex-col items-center justify-center h-44 rounded-lg border-2 border-dashed border-gray-300
                                  cursor-pointer hover:border-red-400 hover:bg-red-50 transition">
                        <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-400 mb-2"></i>
                        <span class="text-sm text-gray-500">Click to upload</span>
                        <span class="text-xs text-gray-400 mt-1">JPG, PNG · max 8MB</span>
                        <input id="thenPhoto" type="file" wire:model="thenPhoto" accept="image/*" class="sr-only">
                    </label>
                @endif

                <div wire:loading wire:target="thenPhoto" class="text-xs text-red-600 mt-1">Uploading…</div>
                @error('thenPhoto') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- NOW --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Now <span class="text-gray-400 font-normal">(recent photo)</span></label>

                @if($nowPhoto)
                    <div class="relative rounded-lg overflow-hidden mb-2">
                        <img src="{{ $nowPhoto->temporaryUrl() }}" class="w-full h-44 object-cover" alt="Now preview">
                        <span class="absolute bottom-2 right-2 text-[11px] font-semibold text-white bg-black/50 rounded px-1.5 py-0.5">Now</span>
                    </div>
                @else
                    <label for="nowPhoto"
                           class="flex flex-col items-center justify-center h-44 rounded-lg border-2 border-dashed border-gray-300
                                  cursor-pointer hover:border-red-400 hover:bg-red-50 transition">
                        <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-400 mb-2"></i>
                        <span class="text-sm text-gray-500">Click to upload</span>
                        <span class="text-xs text-gray-400 mt-1">JPG, PNG · max 8MB</span>
                        <input id="nowPhoto" type="file" wire:model="nowPhoto" accept="image/*" class="sr-only">
                    </label>
                @endif

                <div wire:loading wire:target="nowPhoto" class="text-xs text-red-600 mt-1">Uploading…</div>
                @error('nowPhoto') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Caption --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Caption <span class="text-gray-400 font-normal">(optional)</span></label>
            <input type="text" wire:model="caption" maxlength="200"
                   placeholder="A fun fact or quick note…"
                   class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:ring-red-500 focus:border-red-500">
            @error('caption') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
                class="btn btn-primary w-full"
                wire:loading.attr="disabled"
                wire:target="save">
            <span wire:loading.remove wire:target="save">{{ $existing ? 'Replace Submission' : 'Submit Then & Now' }}</span>
            <span wire:loading wire:target="save">Submitting…</span>
        </button>
    </form>
</div>
