@auth
  {{-- INIT: persist + responsive defaults --}}
  <div
    x-cloak
    x-init="
      (() => {
        const OPEN_KEY = 'drawerOpen';
        const USER_KEY = 'drawerUserSet';
        const mq = window.matchMedia('(min-width: 1024px)');

        // Restore or set a sensible default (open on desktop)
        const saved = localStorage.getItem(OPEN_KEY);
        const userSet = localStorage.getItem(USER_KEY) === '1';
        if (saved !== null) {
          try { drawer = JSON.parse(saved); } catch(_) { drawer = mq.matches; }
        } else {
          drawer = mq.matches; // default: open on desktop, closed on mobile
        }

        // Persist when user toggles
        $watch('drawer', (val) => {
          localStorage.setItem(OPEN_KEY, JSON.stringify(!!val));
          localStorage.setItem(USER_KEY, '1');
        });

        // If user hasn't expressed a preference yet, follow breakpoint changes
        const applyResponsiveDefault = () => {
          if (localStorage.getItem(USER_KEY) !== '1') {
            drawer = mq.matches;
            localStorage.setItem(OPEN_KEY, JSON.stringify(!!drawer));
          }
        };
        if (mq.addEventListener) mq.addEventListener('change', applyResponsiveDefault);
        else mq.addListener(applyResponsiveDefault); // Safari <14

        // Also restore after Livewire soft navigations
        document.addEventListener('livewire:navigated', () => {
          const s = localStorage.getItem(OPEN_KEY);
          if (s !== null) { try { drawer = JSON.parse(s); } catch(_) {} }
        });
      })()
    "
    x-effect="document.body.style.overflow = drawer ? 'hidden' : ''"
  ></div>

  {{-- Overlay --}}
  <div
    x-cloak
    x-show="drawer"
    x-transition.opacity
    class="fixed inset-0 z-[60] bg-black/50"
    @click="drawer=false"
    aria-hidden="true"
  ></div>

  {{-- Off-canvas panel --}}
  <aside
    x-cloak
    x-show="drawer"
    x-transition:enter="transform transition ease-out duration-200"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transform transition ease-in duration-150"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed inset-y-0 left-0 z-[61] w-72 max-w-[90vw] bg-white shadow-xl border-r flex flex-col"
    role="dialog" aria-modal="true" aria-label="Navigation"
    @keydown.escape.stop="drawer=false"
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
      {{-- Primary --}}
      <a
        href="{{ route('home') }}"
        class="flex items-center gap-2 px-2 py-1 rounded {{ request()->routeIs('home') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}"
        @if(request()->routeIs('home')) aria-current="page" @endif
      >
        <i class="fa-solid fa-house {{ request()->routeIs('home') ? 'text-red-600' : 'text-gray-500' }}"></i>
        <span>Home</span>
      </a>

      <a
        href="{{ route('dashboard') }}"
        class="flex items-center gap-2 px-2 py-1 rounded {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}"
        @if(request()->routeIs('dashboard')) aria-current="page" @endif
      >
        <i class="fa-solid fa-calendar-days {{ request()->routeIs('dashboard') ? 'text-red-600' : 'text-gray-500' }}"></i>
        <span>Event Details</span>
      </a>

      <a
        href="{{ route('photos.index') }}"
        class="flex items-center gap-2 px-2 py-1 rounded {{ request()->routeIs('photos.index') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}"
        @if(request()->routeIs('photos.index')) aria-current="page" @endif
      >
        <i class="fa-solid fa-images {{ request()->routeIs('photos.index') ? 'text-red-600' : 'text-gray-500' }}"></i>
        <span>My Photos</span>
      </a>

      <a
        href="{{ route('stories.new') }}"
        class="flex items-center gap-2 px-2 py-1 rounded {{ request()->routeIs('stories.new') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}"
        @if(request()->routeIs('stories.new')) aria-current="page" @endif
      >
        <i class="fa-solid fa-book-open {{ request()->routeIs('stories.new') ? 'text-red-600' : 'text-gray-500' }}"></i>
        <span>Share a Story</span>
      </a>

      <a
        href="{{ route('ideas.new') }}"
        class="flex items-center gap-2 px-2 py-1 rounded {{ request()->routeIs('ideas.new') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}"
        @if(request()->routeIs('ideas.new')) aria-current="page" @endif
      >
        <i class="fa-solid fa-lightbulb {{ request()->routeIs('ideas.new') ? 'text-red-600' : 'text-gray-500' }}"></i>
        <span>Suggest an Idea</span>
      </a>

      <a
        href="{{ route('map.where') }}"
        class="flex items-center gap-2 px-2 py-1 rounded {{ request()->routeIs('map.where') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}"
        @if(request()->routeIs('map.where')) aria-current="page" @endif
      >
        <i class="fa-solid fa-map-location-dot {{ request()->routeIs('map.where') ? 'text-red-600' : 'text-gray-500' }}"></i>
        <span>Where are we now?</span>
      </a>

      <a
        href="{{ route('memorials.wall') }}"
        class="flex items-center gap-2 px-2 py-1 rounded {{ request()->routeIs('memorials.wall') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}"
        @if(request()->routeIs('memorials.wall')) aria-current="page" @endif
      >
        <i class="fa-solid fa-dove {{ request()->routeIs('memorials.wall') ? 'text-red-600' : 'text-gray-500' }}"></i>
        <span>Memorial Wall</span>
      </a>

      @can('admin')
        <div class="mt-3 pt-3 border-t text-xs uppercase tracking-wide text-gray-500">Admin</div>

        <a
          href="{{ route('admin.event') }}"
          class="flex items-center gap-2 px-2 py-1 rounded {{ request()->routeIs('admin.event') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}"
          @if(request()->routeIs('admin.event')) aria-current="page" @endif
        >
          <i class="fa-solid fa-sliders {{ request()->routeIs('admin.event') ? 'text-red-600' : 'text-gray-500' }}"></i>
          <span>Event Settings</span>
        </a>

        <a
          href="{{ route('admin.ideas.index') }}"
          class="flex items-center gap-2 px-2 py-1 rounded {{ request()->routeIs('admin.ideas.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}"
          @if(request()->routeIs('admin.ideas.*')) aria-current="page" @endif
        >
          <i class="fa-solid fa-lightbulb {{ request()->routeIs('admin.ideas.*') ? 'text-red-600' : 'text-gray-500' }}"></i>
          <span>Ideas</span>
        </a>

        <a
          href="{{ route('admin.invites.index') }}"
          class="flex items-center gap-2 px-2 py-1 rounded {{ request()->routeIs('admin.invites.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}"
          @if(request()->routeIs('admin.invites.*')) aria-current="page" @endif
        >
          <i class="fa-solid fa-envelope-open-text {{ request()->routeIs('admin.invites.*') ? 'text-red-600' : 'text-gray-500' }}"></i>
          <span>Invite Requests</span>
        </a>

        <a
          href="{{ route('admin.memorials') }}"
          class="flex items-center gap-2 px-2 py-1 rounded {{ request()->routeIs('admin.memorials.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}"
          @if(request()->routeIs('admin.memorials.*')) aria-current="page" @endif
        >
          <i class="fa-solid fa-dove {{ request()->routeIs('admin.memorials.*') ? 'text-red-600' : 'text-gray-500' }}"></i>
          <span>Memorials</span>
        </a>

        <a
          href="{{ route('admin.photos.index') }}"
          class="flex items-center gap-2 px-2 py-1 rounded {{ request()->routeIs('admin.photos.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}"
          @if(request()->routeIs('admin.photos.*')) aria-current="page" @endif
        >
          <i class="fa-solid fa-images {{ request()->routeIs('admin.photos.*') ? 'text-red-600' : 'text-gray-500' }}"></i>
          <span>Photos</span>
        </a>

        <a
          href="{{ route('admin.stories.index') }}"
          class="flex items-center gap-2 px-2 py-1 rounded {{ request()->routeIs('admin.stories.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}"
          @if(request()->routeIs('admin.stories.*')) aria-current="page" @endif
        >
          <i class="fa-solid fa-book {{ request()->routeIs('admin.stories.*') ? 'text-red-600' : 'text-gray-500' }}"></i>
          <span>Stories</span>
        </a>

        <a
          href="{{ route('admin.users.index') }}"
          class="flex items-center gap-2 px-2 py-1 rounded {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}"
          @if(request()->routeIs('admin.users.*')) aria-current="page" @endif
        >
          <i class="fa-solid fa-users {{ request()->routeIs('admin.users.*') ? 'text-red-600' : 'text-gray-500' }}"></i>
          <span>Users</span>
        </a>
      @endcan
    </nav>

    <div class="border-t px-4 py-3 text-sm">
      <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 rounded text-gray-700 hover:bg-gray-50">
        <i class="fa-solid fa-user-gear {{ request()->routeIs('profile.edit') ? 'text-red-600' : 'text-gray-500' }}"></i>
        <span>Profile</span>
      </a>
      <form method="POST" action="{{ route('logout') }}" class="mt-1">
        @csrf
        <button class="w-full text-left flex items-center gap-2 px-3 py-2 rounded text-gray-700 hover:bg-gray-50">
          <i class="fa-solid fa-right-from-bracket text-gray-500"></i>
          <span>Log out</span>
        </button>
      </form>
    </div>
  </aside>
@endauth