<div class="max-w-4xl mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-6">Event Settings</h1>

  @if(session('status'))
    <div class="mb-4 rounded bg-green-50 text-green-800 px-4 py-3 text-sm">
      {{ session('status') }}
    </div>
  @endif

  <div class="bg-white rounded-xl shadow p-6 space-y-6">
    <div class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="label">Event Name</label>
        <input type="text" wire:model.defer="event_name" class="field" placeholder="{{ config('app.name') }}">
        @error('event_name') <p class="help">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="label">Date</label>
        <input type="date" wire:model.defer="event_date" class="field">
        @error('event_date') <p class="help">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="label">Time</label>
        <input type="text" wire:model.defer="event_time" class="field" placeholder="6:00–10:00 PM">
        @error('event_time') <p class="help">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="label">Venue</label>
        <input type="text" wire:model.defer="venue" class="field" placeholder="The Hall">
        @error('venue') <p class="help">{{ $message }}</p> @enderror
      </div>

      <div class="md:col-span-2">
        <label class="label">Address</label>
        <input type="text" wire:model.defer="address" class="field" placeholder="123 Reunion Way, Your City">
        @error('address') <p class="help">{{ $message }}</p> @enderror
      </div>
    </div>

    <div
      x-data="{
        value: @entangle('details').live,    // keep it in sync both ways
        init() {
          const q = new window.Quill($refs.ed, {
            theme: 'snow',
            placeholder: 'Parking, dress code, schedule…',
            modules: { toolbar: [['bold','italic','underline'], [{ list:'ordered' }, { list:'bullet' }], ['link','clean']] }
          });
          // Livewire -> Quill (initial & subsequent updates)
          q.root.innerHTML = this.value || '';
          this.$watch('value', v => { if (v !== q.root.innerHTML) q.root.innerHTML = v || '' });
          // Quill -> Livewire
          q.on('text-change', () => { this.value = q.root.innerHTML });
        }
      }"
    >
      <label class="label">Additional Information</label>

      {{-- Quill editor (ignored by Livewire DOM diff) --}}
      <div wire:ignore>
        <div x-ref="ed" class="bg-white"></div>
      </div>

      {{-- Hidden input that Livewire WILL read on save --}}
      <input type="hidden" x-model="value" wire:model.defer="details">

      @error('details') <p class="help">{{ $message }}</p> @enderror
    </div>
    
    <div class="pt-2">
      <button wire:click="save" class="btn btn-primary">Save Settings</button>
    </div>
  </div>
</div>