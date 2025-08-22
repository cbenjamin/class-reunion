<div class="mx-auto max-w-md px-4 py-16">
  <div class="bg-white shadow rounded-xl p-6 md:p-8 space-y-6">
    <h1 class="text-2xl font-bold">Set your password</h1>
    <p class="text-sm text-gray-600">Hi! Confirm your name and choose a password to finish signup.</p>

    <form wire:submit.prevent="save" class="space-y-5">
      <div>
        <label class="block text-sm font-medium">Name</label>
        <input type="text" wire:model.defer="name" class="mt-1 w-full rounded-md border-gray-300">
        @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="block text-sm font-medium">Email</label>
        <input type="email" value="{{ $email }}" disabled class="mt-1 w-full rounded-md border-gray-200 bg-gray-50 text-gray-600">
      </div>

      <div>
        <label class="block text-sm font-medium">Password</label>
        <input type="password" wire:model.defer="password" class="mt-1 w-full rounded-md border-gray-300">
        @error('password')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="block text-sm font-medium">Confirm Password</label>
        <input type="password" wire:model.defer="password_confirmation" class="mt-1 w-full rounded-md border-gray-300">
      </div>

      <button class="w-full rounded-lg bg-indigo-600 text-white px-4 py-2 hover:bg-indigo-700">
        Save & Continue
      </button>
    </form>
  </div>
</div>