<x-layouts.guest :title="'Login'">
  <div class="min-h-[calc(100vh-56px)] flex items-center justify-center px-4 py-12
              bg-gradient-to-br from-red-950 via-red-900 to-gray-900">

    <div class="w-full max-w-md">

      {{-- Brand mark --}}
      <div class="text-center mb-8">
        <a href="{{ route('home') }}">
          <img src="{{ asset('images/logo.svg') }}" alt="{{ config('app.name') }}" class="h-12 w-auto mx-auto brightness-0 invert">
        </a>
        <p class="mt-3 text-sm text-white/60">Welcome back — sign in to your account</p>
      </div>

      <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 space-y-6">

        @if (session('status'))
          <div class="rounded-lg bg-green-50 text-green-800 px-4 py-3 text-sm">
            {{ session('status') }}
          </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
          @csrf

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" name="email" type="email" required autofocus autocomplete="username"
                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                   value="{{ old('email') }}">
            @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" name="password" type="password" required autocomplete="current-password"
                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
            @error('password')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
          </div>

          <div class="flex items-center justify-between">
            <label class="inline-flex items-center gap-2 text-sm text-gray-600">
              <input type="checkbox" name="remember" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
              <span>Remember me</span>
            </label>
            @if (Route::has('password.request'))
              <a class="text-sm text-red-700 hover:underline" href="{{ route('password.request') }}">
                Forgot your password?
              </a>
            @endif
          </div>

          <button type="submit"
                  class="w-full inline-flex justify-center items-center rounded-lg bg-red-700 text-white px-5 py-2.5 text-sm font-medium hover:bg-red-800 transition active:scale-95">
            Log In
          </button>
        </form>

        <p class="text-center text-sm text-gray-500">
          Don't have an account?
          <a class="text-red-700 hover:underline font-medium" href="{{ route('invite.create') }}">Request an Invitation</a>
        </p>

      </div>
    </div>
  </div>
</x-layouts.guest>
