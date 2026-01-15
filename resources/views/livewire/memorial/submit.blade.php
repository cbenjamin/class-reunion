<div class="max-w-3xl mx-auto p-6">
  <h1 class="text-2xl font-semibold">Submit a Memorial</h1>
  <p class="text-sm text-gray-600 mt-1">
    Share a tribute for a departed classmate. Submissions are reviewed before appearing on the memorial wall.
  </p>

  @if (session('status'))
    <div class="mt-4 rounded bg-green-50 text-green-800 px-4 py-3 text-sm">{{ session('status') }}</div>
  @endif

  <form wire:submit.prevent="save" class="mt-6 space-y-6">
    <div>
      <label class="label">Classmate’s Name <span class="text-red-500">*</span></label>
      <input type="text" wire:model.defer="classmate_name" class="field" placeholder="e.g., Jordan Smith">
      @error('classmate_name') <div class="help">{{ $message }}</div> @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div>
        <label class="label">Graduation Year</label>
        <input type="text" wire:model.defer="graduation_year" class="field" placeholder="2006">
        @error('graduation_year') <div class="help">{{ $message }}</div> @enderror
      </div>
      <div>
        <label class="label">Your Relationship</label>
        <input type="text" wire:model.defer="relationship" class="field" placeholder="Friend / Teammate / Classmate">
        @error('relationship') <div class="help">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div>
        <label class="label">Your Name</label>
        <input type="text" wire:model.defer="submitter_name" class="field">
        @error('submitter_name') <div class="help">{{ $message }}</div> @enderror
      </div>
      <div>
        <label class="label">Your Email</label>
        <input type="email" wire:model.defer="submitter_email" class="field">
        @error('submitter_email') <div class="help">{{ $message }}</div> @enderror
      </div>
    </div>

    <div>
      <label class="label">Obituary / Reference Link</label>
      <input type="url" wire:model.defer="obituary_url" class="field" placeholder="https://...">
      @error('obituary_url') <div class="help">{{ $message }}</div> @enderror
    </div>

    <div>
      <label class="label">Tribute / Memory</label>
      <textarea rows="5" wire:model.defer="bio" class="field" placeholder="Write a short tribute..."></textarea>
      @error('bio') <div class="help">{{ $message }}</div> @enderror
    </div>

    <div>
      <label class="label">Photo (optional)</label>
      <input type="file" wire:model="photo" class="mt-2 block w-full text-sm">
      @error('photo') <div class="help">{{ $message }}</div> @enderror

      @if ($photo)
        <div class="mt-3">
          <div class="text-xs text-gray-500 mb-1">Preview</div>
          <img src="{{ $photo->temporaryUrl() }}" class="h-32 rounded shadow">
        </div>
      @endif
    </div>

    <div class="pt-2">
      <button class="btn btn-primary">
        Submit Memorial
      </button>
    </div>
  </form>
</div>