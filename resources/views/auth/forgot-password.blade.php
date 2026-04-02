<x-layouts.auth.simple :title="__('Forgot Password')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Forgot your password?')"
            :description="__('Enter your email and we\'ll send you a reset link.')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input
                id="email"
                name="email"
                type="email"
                :label="__('Email address')"
                :value="old('email')"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />
            @if ($errors->has('email'))
                <p class="-mt-3 text-sm text-red-600">{{ $errors->first('email') }}</p>
            @endif

            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Email Password Reset Link') }}
            </flux:button>
        </form>

        <div class="text-center text-sm text-zinc-600">
            <flux:link href="{{ route('login') }}">Back to log in</flux:link>
        </div>
    </div>
</x-layouts.auth.simple>
