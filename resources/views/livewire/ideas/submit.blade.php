<div class="max-w-3xl mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-6">Suggest an Idea</h1>

  @if(session('status'))
    <div class="mb-4 rounded bg-green-50 text-green-800 px-4 py-3 text-sm">
      {{ session('status') }}
    </div>
  @endif

  <div class="bg-white rounded-xl shadow p-6 space-y-6">
    <div class="grid md:grid-cols-2 gap-4">
      <div class="md:col-span-2">
        <label class="label">Title <span class="text-red-500">*</span></label>
        <input type="text" wire:model.defer="title" class="field" placeholder="Karaoke hour, photo booth, ...">
        @error('title') <p class="help">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="label">Category</label>
        <select wire:model.defer="category" class="field">
          <option value="">Select…</option>
          <option>Venue</option>
          <option>Food & Drink</option>
          <option>Entertainment</option>
          <option>Games</option>
          <option>Logistics</option>
          <option>Other</option>
        </select>
        @error('category') <p class="help">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="label">Budget (approx $)</label>
        <input type="number" min="0" step="1" wire:model.defer="budget_estimate" class="field" placeholder="e.g. 250">
        @error('budget_estimate') <p class="help">{{ $message }}</p> @enderror
      </div>

      <div class="md:col-span-2">
        <label class="label">Details</label>
        <textarea wire:model.defer="details" rows="5" class="field" placeholder="Describe the idea, supplies needed, how it would work..."></textarea>
        @error('details') <p class="help">{{ $message }}</p> @enderror
      </div>

      <div class="md:col-span-2 flex items-center gap-6">
        <label class="inline-flex items-center gap-2">
          <input type="checkbox" wire:model.defer="can_volunteer" class="rounded">
          <span class="text-sm">I can help make this happen</span>
        </label>
        <label class="inline-flex items-center gap-2">
          <input type="checkbox" wire:model.defer="anonymous" class="rounded">
          <span class="text-sm">Share anonymously</span>
        </label>
      </div>
    </div>

    {{-- Honeypot --}}
    <input type="text" wire:model.defer="website" class="hidden" tabindex="-1" autocomplete="off">

    <div class="pt-2">
      <button wire:click="submit" class="btn btn-primary">Submit Idea</button>
    </div>
  </div>
</div>