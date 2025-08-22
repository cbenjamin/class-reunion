<div class="mx-auto max-w-2xl px-4 py-12">
  <div class="bg-white shadow rounded-xl p-6 md:p-8">
    <h1 class="text-2xl font-bold mb-6">Request an Invitation</h1>

    @if($submitted)
      <div class="rounded bg-green-50 text-green-800 px-4 py-3">
        Thanks! We’ll review your request and email you.
      </div>
    @else
      <form wire:submit.prevent="submit" class="space-y-6">
        <div>
          <label class="block text-sm font-medium">Full Name</label>
          <input type="text" wire:model.defer="full_name" class="mt-1 w-full rounded-md border-gray-300">
          @error('full_name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
          <label class="block text-sm font-medium">Email</label>
          <input type="email" wire:model.defer="email" class="mt-1 w-full rounded-md border-gray-300">
          @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium">Graduation Year</label>
            <input type="text" wire:model.defer="grad_year" class="mt-1 w-full rounded-md border-gray-300">
          </div>
          <div>
            <label class="block text-sm font-medium">Maiden Name (optional)</label>
            <input type="text" wire:model.defer="maiden_name" class="mt-1 w-full rounded-md border-gray-300">
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium">What would make this reunion fun for you?</label>
          <textarea rows="4" wire:model.defer="interest" class="mt-1 w-full rounded-md border-gray-300"></textarea>
        </div>

        <div class="flex items-center gap-3">
          <button class="rounded-lg bg-indigo-600 text-white px-4 py-2 hover:bg-indigo-700">
            Submit
          </button>
          <a href="{{ route('login') }}" class="text-sm text-gray-600">Already invited? Log in</a>
        </div>
      </form>
    @endif
  </div>
</div>