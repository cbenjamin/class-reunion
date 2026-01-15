@auth
<div
  x-data
  @keydown.escape.window="navOpen = false"
  x-cloak
  class="lg:hidden"
>
  {{-- Overlay --}}
  <div
    x-show="navOpen"
    x-transition.opacity
    class="fixed inset-0 z-40 bg-black/50"
    @click="navOpen = false"
    aria-hidden="true"
  ></div>

  {{-- Drawer --}}
  <aside
    id="mobile-drawer"
    x-show="navOpen"
    x-transition:enter="transform transition ease-out duration-200"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transform transition ease-in duration-150"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed z-50 inset-y-0 left-0 w-80 max-w-[85vw] bg-white shadow-xl border-r"
    role="dialog" aria-modal="true" aria-label="Navigation"
  >
    {{-- Header --}}
    <div class="h-14 flex items-center justify-between px-4 border-b">
      <div class="font-semibold">Menu</div>
      <button
        class="p-2 rounded-md hover:bg-gray-100"
        @click="navOpen = false"
        aria-label="Close menu"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
      </button>
    </div>

    {{-- Links --}}
    <nav class="px-4 py-3 text-sm space-y-1">
          <a class="block px-2 py-1 rounded hover:bg-gray-50" href="/">Home</a>
          <a class="block px-2 py-1 rounded {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}" href="{{ route('dashboard') }}">Event Details</a>
          <a class="block px-2 py-1 rounded {{ request()->routeIs('photos.index') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}" href="{{ route('photos.index') }}">My Photos</a>
          <a class="block px-2 py-1 rounded {{ request()->routeIs('stories.new') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}" href="{{ route('stories.new') }}">Share a Story</a>
          <a class="block px-2 py-1 rounded {{ request()->routeIs('ideas.new') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}"
   href="{{ route('ideas.new') }}">Suggest an Idea</a>
          <a class="block px-2 py-1 rounded {{ request()->routeIs('memorials.wall') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}"
   href="{{ route('memorials.wall') }}">Memorial Wall</a>
      @can('admin')
        <div class="mt-3 pt-3 border-t text-xs uppercase tracking-wide text-gray-500">Admin</div>

          <a class="block px-2 py-1 rounded {{ request()->routeIs('admin.event') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}"
             href="{{ route('admin.event') }}">
            Event Settings
          </a>
          <a class="block px-2 py-1 rounded {{ request()->routeIs('admin.ideas.*') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}"
             href="{{ route('admin.ideas.index') }}">Ideas</a>
           <a class="block px-2 py-1 rounded {{ request()->routeIs('admin.invites.*') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}"
     href="{{ route('admin.invites.index') }}">Invites</a>
            <a class="block px-2 py-1 rounded {{ request()->routeIs('admin.memorials.*') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}"
     href="{{ route('admin.memorials') }}">Memorials</a>
            <a class="block px-2 py-1 rounded {{ request()->routeIs('admin.photos.*') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}" href="{{ route('admin.photos.index') }}">Photos</a>
            <a class="block px-2 py-1 rounded {{ request()->routeIs('admin.stories.*') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}" href="{{ route('admin.stories.index') }}">Stories</a>
            <a class="block px-2 py-1 rounded {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}"
     href="{{ route('admin.users.index') }}">Users</a>
      @endcan
    </nav>
  </aside>
</div>
@endauth