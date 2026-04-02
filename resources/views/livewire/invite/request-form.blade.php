<div class="flex flex-col gap-6">
    <x-auth-header
        :title="__('Request Access')"
        :description="__('Fill out the form below and an admin will review your request.')"
    />

    @if($submitted)
        <div class="rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800 text-center">
            Thanks! We'll review your request and email you when you're approved.
        </div>
        <div class="text-center text-sm">
            <flux:link :href="route('login')" wire:navigate>Back to log in</flux:link>
        </div>
    @else
        <form wire:submit.prevent="submit" class="flex flex-col gap-5">
            <flux:input
                wire:model="full_name"
                :label="__('Full Name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                placeholder="Your full name"
            />
            @error('full_name')
                <p class="-mt-3 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <flux:input
                wire:model="email"
                :label="__('Email address')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />
            @error('email')
                <p class="-mt-3 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <flux:input
                        wire:model="grad_year"
                        :label="__('Graduation Year')"
                        type="text"
                        placeholder="e.g. 1998"
                    />
                </div>
                <div>
                    <flux:input
                        wire:model="maiden_name"
                        :label="__('Maiden Name')"
                        type="text"
                        placeholder="Optional"
                    />
                </div>
            </div>

            <flux:textarea
                wire:model="interest"
                :label="__('What would make this reunion fun for you?')"
                rows="3"
                placeholder="Optional"
            />

            <flux:input
                wire:model="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Choose a password')"
                viewable
            />
            @error('password')
                <p class="-mt-3 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <flux:input
                wire:model="password_confirmation"
                :label="__('Confirm Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm your password')"
                viewable
            />

            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Request Access') }}
            </flux:button>
        </form>

        <div class="text-center text-sm text-zinc-600">
            Already have access?
            <flux:link :href="route('login')" wire:navigate>Log in</flux:link>
        </div>
    @endif
</div>
