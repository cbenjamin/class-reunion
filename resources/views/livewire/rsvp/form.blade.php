<div class="mx-auto max-w-3xl px-5 py-10">
  <div class="bubble">
    <div class="bubble-inner">
      <div class="flex items-start justify-between gap-4">
        <div>
          <h1 class="bubble-title">RSVP</h1>
          <p class="bubble-sub">Let us know if you can make it.</p>
        </div>
      </div>

      @if(!$rsvpEnabled)
        <div class="mt-6 rounded-xl bg-gray-50 ring-1 ring-black/5 p-4 text-sm text-gray-700">
          RSVP is not enabled yet. Check back soon!
        </div>
      @else
        @if (session('status'))
          <div class="mt-6 rounded-xl bg-green-50 ring-1 ring-green-200 p-4 text-sm text-green-800">
            {{ session('status') }}
          </div>
        @endif

        <form wire:submit.prevent="save" class="mt-6 space-y-6">
          {{-- Status --}}
          <div>
            <label class="label">Will you attend?</label>
            <div class="mt-2 grid sm:grid-cols-3 gap-3">
              <label class="rounded-xl ring-1 ring-black/5 bg-white p-4 flex items-center gap-3 cursor-pointer">
                <input type="radio" class="text-red-600 focus:ring-red-600" wire:model.live="status" value="yes">
                <div>
                  <div class="font-medium">Yes</div>
                  <div class="text-xs text-gray-500">I’ll be there</div>
                </div>
              </label>

              <label class="rounded-xl ring-1 ring-black/5 bg-white p-4 flex items-center gap-3 cursor-pointer">
                <input type="radio" class="text-red-600 focus:ring-red-600" wire:model.live="status" value="maybe">
                <div>
                  <div class="font-medium">Maybe</div>
                  <div class="text-xs text-gray-500">Still deciding</div>
                </div>
              </label>

              <label class="rounded-xl ring-1 ring-black/5 bg-white p-4 flex items-center gap-3 cursor-pointer">
                <input type="radio" class="text-red-600 focus:ring-red-600" wire:model.live="status" value="no">
                <div>
                  <div class="font-medium">No</div>
                  <div class="text-xs text-gray-500">Can’t make it</div>
                </div>
              </label>
            </div>
            @error('status') <p class="help">{{ $message }}</p> @enderror
          </div>

          {{-- Guests --}}
          <div>
            <label class="label">Guests</label>
            <input type="number" min="0" max="10" class="field" wire:model.live="guest_count">
            <p class="text-xs text-gray-500 mt-1">How many guests are you bringing? (0–10)</p>
            @error('guest_count') <p class="help">{{ $message }}</p> @enderror
          </div>

          {{-- Note --}}
          <div>
            <label class="label">Note (optional)</label>
            <textarea rows="4" class="field" wire:model.live="note"></textarea>
            @error('note') <p class="help">{{ $message }}</p> @enderror
          </div>

          <div class="flex items-center gap-3">
            <button class="btn btn-danger" type="submit">Save RSVP</button>
            <span wire:loading class="text-sm text-gray-500">Saving…</span>
          </div>
        </form>
      @endif
    </div>
  </div>
</div>