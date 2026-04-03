<div>

    <div class="mb-6 text-center">
        <h1 class="text-xl font-semibold text-gray-900">Request Access</h1>
        <p class="mt-1 text-sm text-gray-500">Fill out the form below and an admin will review your request.</p>
    </div>

    @if($submitted)
        <div class="rounded-lg bg-green-50 border border-green-200 px-4 py-4 text-sm text-green-800 text-center space-y-2">
            <p class="font-medium">Thanks for requesting access!</p>
            <p>We'll review your request and email you once you're approved. You can then log in with the password you just set.</p>
        </div>
        <p class="mt-4 text-center text-sm text-gray-500">
            <a href="{{ route('login') }}" class="text-red-700 hover:underline font-medium">Back to log in</a>
        </p>
    @else
        <form wire:submit.prevent="submit" class="space-y-4">

            <div>
                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                <input wire:model="full_name" type="text" required autofocus autocomplete="name"
                       placeholder="Your full name"
                       class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                @error('full_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email address</label>
                <input wire:model="email" type="email" required autocomplete="email"
                       placeholder="email@example.com"
                       class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Graduation Year</label>
                    <input wire:model="grad_year" type="text" placeholder="e.g. 2006"
                           class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Maiden Name</label>
                    <input wire:model="maiden_name" type="text" placeholder="Optional"
                           class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">What would make this reunion fun for you?</label>
                <textarea wire:model="interest" rows="2" placeholder="Optional"
                          class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm resize-none"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input wire:model="password" type="password" required autocomplete="new-password"
                       placeholder="Choose a password (min 8 characters)"
                       class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input wire:model="password_confirmation" type="password" required autocomplete="new-password"
                       placeholder="Confirm your password"
                       class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
            </div>

            <button type="submit"
                    class="w-full inline-flex justify-center items-center rounded-lg bg-red-700 text-white px-5 py-2.5 text-sm font-medium hover:bg-red-800 transition active:scale-95 mt-2">
                <span wire:loading.remove>Request Access</span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Submitting…
                </span>
            </button>
        </form>

        <p class="mt-5 text-center text-sm text-gray-500">
            Already have access?
            <a href="{{ route('login') }}" class="text-red-700 hover:underline font-medium">Log in</a>
        </p>
    @endif

</div>
