@props(['title' => null])

@auth
    {{-- Authenticated layout: show the sidebar --}}
    <x-layouts.app.sidebar :title="$title">
        <flux:main>
            {{ $slot }}
        </flux:main>
    </x-layouts.app.sidebar>
@else
    {{-- Guest layout: no sidebar --}}
    <div class="min-h-screen bg-gray-100">
        <flux:main>
            {{ $slot }}
        </flux:main>
    </div>
@endauth