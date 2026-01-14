<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? config('app.name', 'Reunion') }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  @livewireStyles
</head>
<body
  x-data="{ navOpen: false }"
  x-effect="document.documentElement.classList.toggle('overflow-hidden', navOpen)"
  class="font-sans antialiased bg-gray-100"
>
  {{-- Top bar is always present; solid for app pages --}}
  @include('layouts.topnav', ['variant' => 'solid'])

  {{-- Mobile drawer --}}
  @include('layouts.subnav')

  <div class="min-h-screen flex">
    @auth
      {{-- Desktop sidebar --}}
      <aside class="hidden lg:block w-64 shrink-0 bg-white border-r">
        <div class="p-4 font-semibold">Menu</div>
        <nav class="px-4 pb-4 text-sm space-y-2">
          <a class="block px-2 py-1 rounded hover:bg-gray-50" href="/">Home</a>
          <a class="block px-2 py-1 rounded {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}" href="{{ route('dashboard') }}">Event Details</a>
          <a class="block px-2 py-1 rounded {{ request()->routeIs('photos.index') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}" href="{{ route('photos.index') }}">My Photos</a>
          <a class="block px-2 py-1 rounded {{ request()->routeIs('stories.new') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}" href="{{ route('stories.new') }}">Share a Story</a>
          <a class="block px-2 py-1 rounded {{ request()->routeIs('ideas.new') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}"
   href="{{ route('ideas.new') }}">Suggest an Idea</a>

          @can('admin')
          <a class="block px-2 py-1 rounded {{ request()->routeIs('admin.event') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}"
             href="{{ route('admin.event') }}">
            Admin ▸ Event Settings
          </a>
          <a class="block px-2 py-1 rounded {{ request()->routeIs('admin.ideas.*') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}"
             href="{{ route('admin.ideas.index') }}">Admin ▸ Ideas</a>
           <a class="block px-2 py-1 rounded {{ request()->routeIs('admin.invites.*') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}"
     href="{{ route('admin.invites.index') }}">Admin ▸ Invites</a>

            <a class="block px-2 py-1 rounded {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}"
     href="{{ route('admin.users.index') }}">Admin ▸ Users</a>
            <a class="block px-2 py-1 rounded {{ request()->routeIs('admin.photos.*') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}" href="{{ route('admin.photos.index') }}">Admin ▸ Photos</a>
            <a class="block px-2 py-1 rounded {{ request()->routeIs('admin.stories.*') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}" href="{{ route('admin.stories.index') }}">Admin ▸ Stories</a>
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