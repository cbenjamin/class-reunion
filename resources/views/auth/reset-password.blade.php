<x-layouts.auth.simple :title="__('Reset Password')">

    <div class="mb-6 text-center">
        <h1 class="text-xl font-semibold text-gray-900">Reset your password</h1>
        <p class="mt-1 text-sm text-gray-500">Enter your new password below.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
            <input id="email" name="email" type="email" required autofocus autocomplete="username"
                   value="{{ old('email', $request->email) }}"
                   placeholder="email@example.com"
                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
            <input id="password" name="password" type="password" required autocomplete="new-password"
                   placeholder="New password"
                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                   placeholder="Confirm new password"
                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
            @error('password_confirmation')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full inline-flex justify-center items-center rounded-lg bg-red-700 text-white px-5 py-2.5 text-sm font-medium hover:bg-red-800 transition active:scale-95">
            Reset Password
        </button>
    </form>

</x-layouts.auth.simple>
