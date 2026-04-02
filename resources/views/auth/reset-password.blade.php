<x-layouts.auth.simple :title="__('Reset Password')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Reset your password')"
            :description="__('Enter your new password below.')"
        />

        <form method="POST" action="{{ route('password.store') }}" class="flex flex-col gap-6">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <flux:input
                id="email"
                name="email"
                type="email"
                :label="__('Email address')"
                :value="old('email', $request->email)"
                required
                autofocus
                autocomplete="username"
                placeholder="email@example.com"
            />
            @if ($errors->has('email'))
                <p class="-mt-3 text-sm text-red-600">{{ $errors->first('email') }}</p>
            @endif

            <flux:input
                id="password"
                name="password"
                type="password"
                :label="__('New Password')"
                required
                autocomplete="new-password"
                :placeholder="__('New password')"
                viewable
            />
            @if ($errors->has('password'))
                <p class="-mt-3 text-sm text-red-600">{{ $errors->first('password') }}</p>
            @endif

            <flux:input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                :label="__('Confirm Password')"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm new password')"
                viewable
            />
            @if ($errors->has('password_confirmation'))
                <p class="-mt-3 text-sm text-red-600">{{ $errors->first('password_confirmation') }}</p>
            @endif

            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Reset Password') }}
            </flux:button>
        </form>
    </div>
</x-layouts.auth.simple>
