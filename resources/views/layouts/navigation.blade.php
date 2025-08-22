<nav class="bg-white border-b">
  <div class="mx-auto max-w-7xl px-4 py-3 flex items-center gap-4">
    <a href="{{ url('/') }}" class="font-semibold">Class of '06 - 20 Year Reunion</a>
    <!--<a href="{{ route('invite.create') }}" class="text-sm text-gray-600 hover:text-gray-900">Request Invitation</a>-->

    @auth
      <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">Event Details</a>
      <a href="{{ route('photos.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Photos</a>
      @can('admin')
        <a href="{{ route('admin.invites.index') }}" class="text-sm text-indigo-600">Admin</a>
      @endcan

      <div class="ml-auto flex items-center gap-3">
        <a href="{{ route('profile.edit') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ auth()->user()->name }}</a>
        <form method="POST" action="{{ route('logout') }}">@csrf
          <button class="text-sm text-gray-600 hover:text-gray-900">Logout</button>
        </form>
      </div>
    @else
      <a href="{{ route('login') }}" class="ml-auto text-sm text-gray-600 hover:text-gray-900">Log in</a>
      <!--<a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-gray-900">Register</a>-->
    @endauth
  </div>
</nav>