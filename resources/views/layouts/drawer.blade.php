@auth
  {{-- Overlay --}}
  <div
    x-show="drawer"
    x-transition.opacity
    class="fixed inset-0 z-[60] bg-black/50"
    @click="drawer=false"
    aria-hidden="true"
  ></div>

  {{-- Off-canvas panel --}}
  <aside
    x-show="drawer"
    x-transition:enter="transform transition ease-out duration-200"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transform transition ease-in duration-150"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed inset-y-0 left-0 z-[61] w-72 max-w-[90vw] bg-white shadow-xl border-r flex flex-col"
    role="dialog" aria-modal="true" aria-label="Navigation"
  >
    <div class="flex items-center justify-between px-4 h-14 border-b">
      <div class="font-semibold">{{ config('app.name') }}</div>
      <button
        class="inline-flex items-center justify-center h-8 w-8 rounded-md hover:bg-gray-100"
        @click="drawer=false"
        aria-label="Close navigation"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-800" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <nav class="px-3 py-4 text-sm space-y-1 flex-1 overflow-y-auto">
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
     href="{{ route('admin.invites.index') }}">Invite Requests</a>
            <a class="block px-2 py-1 rounded {{ request()->routeIs('admin.memorials.*') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}"
     href="{{ route('admin.memorials') }}">Memorials</a>
            <a class="block px-2 py-1 rounded {{ request()->routeIs('admin.photos.*') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}" href="{{ route('admin.photos.index') }}">Photos</a>
            <a class="block px-2 py-1 rounded {{ request()->routeIs('admin.stories.*') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}" href="{{ route('admin.stories.index') }}">Stories</a>
            <a class="block px-2 py-1 rounded {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 text-gray-900' : 'hover:bg-gray-50' }}"
     href="{{ route('admin.users.index') }}">Users</a>
          @endcan
    </nav>

    <div class="border-t px-4 py-3 text-sm">
      <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded text-gray-700 hover:bg-gray-50">Profile</a>
      <form method="POST" action="{{ route('logout') }}" class="mt-1">
        @csrf
        <button class="w-full text-left px-3 py-2 rounded text-gray-700 hover:bg-gray-50">Log out</button>
      </form>
    </div>
  </aside>
@endauth