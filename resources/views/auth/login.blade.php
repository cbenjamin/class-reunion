{{-- Uses our guest layout --}}
<x-layouts.guest :title="'Login'">
  <div class="mx-auto max-w-md px-4 py-16">
    <div class="bg-white shadow rounded-xl p-6 md:p-8 space-y-6">
      <h1 class="text-2xl font-bold">Log in</h1>

      <!-- Session Status -->
      @if (session('status'))
        <div class="rounded bg-green-50 text-green-800 px-4 py-3 text-sm">
          {{ session('status') }}
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
          <label for="email" class="block text-sm font-medium">Email</label>
          <input id="email" name="email" type="email" required autofocus autocomplete="username"
                 class="mt-1 w-full rounded-md border-gray-300" value="{{ old('email') }}">
          @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
          <label for="password" class="block text-sm font-medium">Password</label>
          <input id="password" name="password" type="password" required autocomplete="current-password"
                 class="mt-1 w-full rounded-md border-gray-300">
          @error('password')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center justify-between">
          <label class="inline-flex items-center gap-2 text-sm text-gray-600">
            <input type="checkbox" name="remember" class="rounded border-gray-300">
            <span>Remember me</span>
          </label>
          @if (Route::has('password.request'))
            <a class="text-sm text-indigo-600 hover:underline" href="{{ route('password.request') }}">
              Forgot your password?
            </a>
          @endif
        </div>

        <x-primary-button>
          Log in
        </x-primary-button>
      </form>

      <p class="text-sm text-gray-600">
        Don’t have an account?
        <a class="text-indigo-600 hover:underline" href="{{ route('invite.create') }}">Request an Invitation</a>
      </p>
    </div>
  </div>
</x-layouts.guest>