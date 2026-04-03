<x-layouts.auth.simple :title="__('Forgot Password')">

    <div class="mb-6 text-center">
        <h1 class="text-xl font-semibold text-gray-900">Forgot your password?</h1>
        <p class="mt-1 text-sm text-gray-500">Enter your email and we'll send you a reset link.</p>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800 text-center">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
            <input id="email" name="email" type="email" required autofocus autocomplete="email"
                   value="{{ old('email') }}"
                   placeholder="email@example.com"
                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full inline-flex justify-center items-center rounded-lg bg-red-700 text-white px-5 py-2.5 text-sm font-medium hover:bg-red-800 transition active:scale-95">
            Email Password Reset Link
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        <a href="{{ route('login') }}" class="text-red-700 hover:underline font-medium">Back to log in</a>
    </p>

</x-layouts.auth.simple>
