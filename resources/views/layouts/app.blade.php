@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? config('app.name', 'Reunion') }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-100">
  @include('layouts.navigation')

  <div class="min-h-screen flex">
    @auth
      <aside class="w-64 shrink-0 bg-white border-r">
        <div class="p-4 font-semibold">Menu</div>
        <nav class="px-4 pb-4 text-sm space-y-2">
          <a class="block hover:underline" href="{{ route('dashboard') }}">Event Details</a>
          <a class="block hover:underline" href="{{ route('photos.index') }}">Photos</a>
          @can('admin')
            <a class="block hover:underline" href="{{ route('admin.invites.index') }}">Admin ▸ Invites</a>
            <a class="block hover:underline" href="{{ route('admin.photos.index') }}">Admin ▸ Photos</a>
          @endcan
        </nav>
      </aside>
    @endauth

    <main class="flex-1">
      {{ $slot }}
    </main>
  </div>

  @livewireScripts
</body>
</html>