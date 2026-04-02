@auth
<aside
  class="hidden md:flex md:fixed md:inset-y-0 md:left-0 md:w-72 md:z-40
         bg-zinc-900 pt-14 flex-col"
  aria-label="Sidebar Navigation">

  <nav class="px-3 py-4 text-sm space-y-0.5 flex-1 overflow-y-auto">

    <a href="{{ route('home') }}"
       class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('home') ? 'bg-white/10 text-white' : 'text-zinc-300 hover:bg-white/5 hover:text-white' }}"
       @if(request()->routeIs('home')) aria-current="page" @endif>
      <i class="fa-solid fa-house w-4 text-center {{ request()->routeIs('home') ? 'text-red-400' : 'text-zinc-500' }}"></i>
      <span>Home</span>
    </a>

    <a href="{{ route('dashboard') }}"
       class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white' : 'text-zinc-300 hover:bg-white/5 hover:text-white' }}"
       @if(request()->routeIs('dashboard')) aria-current="page" @endif>
      <i class="fa-solid fa-calendar-days w-4 text-center {{ request()->routeIs('dashboard') ? 'text-red-400' : 'text-zinc-500' }}"></i>
      <span>Event Details</span>
    </a>

    <a href="{{ route('photos.index') }}"
       class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('photos.index') ? 'bg-white/10 text-white' : 'text-zinc-300 hover:bg-white/5 hover:text-white' }}"
       @if(request()->routeIs('photos.index')) aria-current="page" @endif>
      <i class="fa-solid fa-images w-4 text-center {{ request()->routeIs('photos.index') ? 'text-red-400' : 'text-zinc-500' }}"></i>
      <span>My Photos</span>
    </a>

    <a href="{{ route('stories.new') }}"
       class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('stories.new') ? 'bg-white/10 text-white' : 'text-zinc-300 hover:bg-white/5 hover:text-white' }}"
       @if(request()->routeIs('stories.new')) aria-current="page" @endif>
      <i class="fa-solid fa-book-open w-4 text-center {{ request()->routeIs('stories.new') ? 'text-red-400' : 'text-zinc-500' }}"></i>
      <span>Share a Story</span>
    </a>

    <a href="{{ route('ideas.new') }}"
       class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('ideas.new') ? 'bg-white/10 text-white' : 'text-zinc-300 hover:bg-white/5 hover:text-white' }}"
       @if(request()->routeIs('ideas.new')) aria-current="page" @endif>
      <i class="fa-solid fa-lightbulb w-4 text-center {{ request()->routeIs('ideas.new') ? 'text-red-400' : 'text-zinc-500' }}"></i>
      <span>Suggest an Idea</span>
    </a>

    <a href="{{ route('map.where') }}"
       class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('map.where') ? 'bg-white/10 text-white' : 'text-zinc-300 hover:bg-white/5 hover:text-white' }}"
       @if(request()->routeIs('map.where')) aria-current="page" @endif>
      <i class="fa-solid fa-map-location-dot w-4 text-center {{ request()->routeIs('map.where') ? 'text-red-400' : 'text-zinc-500' }}"></i>
      <span>Where are we now?</span>
    </a>

    <a href="{{ route('memorials.wall') }}"
       class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('memorials.wall') ? 'bg-white/10 text-white' : 'text-zinc-300 hover:bg-white/5 hover:text-white' }}"
       @if(request()->routeIs('memorials.wall')) aria-current="page" @endif>
      <i class="fa-solid fa-dove w-4 text-center {{ request()->routeIs('memorials.wall') ? 'text-red-400' : 'text-zinc-500' }}"></i>
      <span>Memorial Wall</span>
    </a>

    <a href="{{ route('then-now.wall') }}"
       class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('then-now.*') ? 'bg-white/10 text-white' : 'text-zinc-300 hover:bg-white/5 hover:text-white' }}"
       @if(request()->routeIs('then-now.*')) aria-current="page" @endif>
      <i class="fa-solid fa-clock-rotate-left w-4 text-center {{ request()->routeIs('then-now.*') ? 'text-red-400' : 'text-zinc-500' }}"></i>
      <span>Then &amp; Now</span>
    </a>

    @php $rsvpEnabled = (bool) \App\Models\EventSetting::query()->value('rsvp_enabled'); @endphp
    @if($rsvpEnabled)
      <a href="{{ route('rsvp.form') }}"
         class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('rsvp.form') ? 'bg-white/10 text-white' : 'text-zinc-300 hover:bg-white/5 hover:text-white' }}"
         @if(request()->routeIs('rsvp.form')) aria-current="page" @endif>
        <i class="fa-solid fa-circle-check w-4 text-center {{ request()->routeIs('rsvp.form') ? 'text-red-400' : 'text-zinc-500' }}"></i>
        <span>RSVP</span>
      </a>
    @endif

    @can('admin')
      <div class="mt-4 pt-4 border-t border-white/10 px-3 pb-1 text-[11px] uppercase tracking-widest text-zinc-500 font-semibold">Admin</div>

      <a href="{{ route('admin.event') }}"
         class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('admin.event') ? 'bg-white/10 text-white' : 'text-zinc-300 hover:bg-white/5 hover:text-white' }}"
         @if(request()->routeIs('admin.event')) aria-current="page" @endif>
        <i class="fa-solid fa-sliders w-4 text-center {{ request()->routeIs('admin.event') ? 'text-red-400' : 'text-zinc-500' }}"></i>
        <span>Event Settings</span>
      </a>

      <a href="{{ route('admin.ideas.index') }}"
         class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('admin.ideas.*') ? 'bg-white/10 text-white' : 'text-zinc-300 hover:bg-white/5 hover:text-white' }}"
         @if(request()->routeIs('admin.ideas.*')) aria-current="page" @endif>
        <i class="fa-solid fa-lightbulb w-4 text-center {{ request()->routeIs('admin.ideas.*') ? 'text-red-400' : 'text-zinc-500' }}"></i>
        <span>Ideas</span>
      </a>

      <a href="{{ route('admin.invites.index') }}"
         class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('admin.invites.*') ? 'bg-white/10 text-white' : 'text-zinc-300 hover:bg-white/5 hover:text-white' }}"
         @if(request()->routeIs('admin.invites.*')) aria-current="page" @endif>
        <i class="fa-solid fa-envelope-open-text w-4 text-center {{ request()->routeIs('admin.invites.*') ? 'text-red-400' : 'text-zinc-500' }}"></i>
        <span>Invite Requests</span>
      </a>

      <a href="{{ route('admin.memorials') }}"
         class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('admin.memorials.*') ? 'bg-white/10 text-white' : 'text-zinc-300 hover:bg-white/5 hover:text-white' }}"
         @if(request()->routeIs('admin.memorials.*')) aria-current="page" @endif>
        <i class="fa-solid fa-dove w-4 text-center {{ request()->routeIs('admin.memorials.*') ? 'text-red-400' : 'text-zinc-500' }}"></i>
        <span>Memorials</span>
      </a>

      <a href="{{ route('admin.photos.index') }}"
         class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('admin.photos.*') ? 'bg-white/10 text-white' : 'text-zinc-300 hover:bg-white/5 hover:text-white' }}"
         @if(request()->routeIs('admin.photos.*')) aria-current="page" @endif>
        <i class="fa-solid fa-images w-4 text-center {{ request()->routeIs('admin.photos.*') ? 'text-red-400' : 'text-zinc-500' }}"></i>
        <span>Photos</span>
      </a>

      <a href="{{ route('admin.then-now.index') }}"
         class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('admin.then-now.*') ? 'bg-white/10 text-white' : 'text-zinc-300 hover:bg-white/5 hover:text-white' }}"
         @if(request()->routeIs('admin.then-now.*')) aria-current="page" @endif>
        <i class="fa-solid fa-clock-rotate-left w-4 text-center {{ request()->routeIs('admin.then-now.*') ? 'text-red-400' : 'text-zinc-500' }}"></i>
        <span>Then &amp; Now</span>
      </a>

      <a href="{{ route('admin.stories.index') }}"
         class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('admin.stories.*') ? 'bg-white/10 text-white' : 'text-zinc-300 hover:bg-white/5 hover:text-white' }}"
         @if(request()->routeIs('admin.stories.*')) aria-current="page" @endif>
        <i class="fa-solid fa-book w-4 text-center {{ request()->routeIs('admin.stories.*') ? 'text-red-400' : 'text-zinc-500' }}"></i>
        <span>Stories</span>
      </a>

      <a href="{{ route('admin.users.index') }}"
         class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-white/10 text-white' : 'text-zinc-300 hover:bg-white/5 hover:text-white' }}"
         @if(request()->routeIs('admin.users.*')) aria-current="page" @endif>
        <i class="fa-solid fa-users w-4 text-center {{ request()->routeIs('admin.users.*') ? 'text-red-400' : 'text-zinc-500' }}"></i>
        <span>Users</span>
      </a>

      <a href="{{ route('admin.rsvps.index') }}"
         class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('admin.rsvps.*') ? 'bg-white/10 text-white' : 'text-zinc-300 hover:bg-white/5 hover:text-white' }}"
         @if(request()->routeIs('admin.rsvps.*')) aria-current="page" @endif>
        <i class="fa-solid fa-clipboard-check w-4 text-center {{ request()->routeIs('admin.rsvps.*') ? 'text-red-400' : 'text-zinc-500' }}"></i>
        <span>RSVPs</span>
      </a>
    @endcan
  </nav>

  <div class="border-t border-white/10 px-3 py-3">
    <a href="{{ route('profile.edit') }}"
       class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-slate-300 hover:bg-white/5 hover:text-white">
      <i class="fa-solid fa-user-gear w-4 text-center {{ request()->routeIs('profile.edit') ? 'text-red-400' : 'text-zinc-500' }}"></i>
      <span class="text-sm">Profile</span>
    </a>
    <form method="POST" action="{{ route('logout') }}" class="mt-0.5">
      @csrf
      <button class="w-full text-left flex items-center gap-2.5 px-3 py-2 rounded-lg text-zinc-300 hover:bg-white/5 hover:text-white text-sm">
        <i class="fa-solid fa-right-from-bracket w-4 text-center text-slate-500"></i>
        <span>Log out</span>
      </button>
    </form>
  </div>
</aside>
@endauth
