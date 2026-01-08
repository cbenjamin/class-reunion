<div class="max-w-6xl mx-auto px-4 py-8">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Users</h1>

    <div class="flex items-center gap-2">
      <input type="text" wire:model.debounce.300ms="search"
             class="field !mt-0" placeholder="Search name or email…">

      <div class="inline-flex rounded-lg bg-gray-100 p-1 text-sm">
        <button type="button" wire:click="$set('status','approved')"
                class="px-3 py-1.5 rounded-md {{ $status==='approved' ? 'bg-white shadow text-gray-900' : 'text-gray-600' }}">
          Approved
        </button>
        <button type="button" wire:click="$set('status','denied')"
                class="px-3 py-1.5 rounded-md {{ $status==='denied' ? 'bg-white shadow text-gray-900' : 'text-gray-600' }}">
          Denied
        </button>
      </div>
    </div>
  </div>

  @if(session('status'))
    <div class="mb-4 rounded bg-green-50 text-green-800 px-4 py-3 text-sm">{{ session('status') }}</div>
  @endif

  @if($users->isEmpty())
    <p class="text-sm text-gray-600">No users in this view.</p>
  @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      @foreach($users as $u)
        <article class="bg-white rounded-xl shadow p-4">
          <div class="flex items-start justify-between">
            <div>
              <div class="font-semibold">{{ $u->name }}</div>
              <div class="text-sm text-gray-600">{{ $u->email }}</div>
            </div>
            <span class="text-[10px] px-2 py-0.5 rounded-full {{ $u->is_approved ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
              {{ $u->is_approved ? 'Approved' : 'Denied' }}
            </span>
          </div>

          <div class="mt-4 flex flex-wrap gap-2">
            @if($u->is_approved)
              <button wire:click="deny({{ $u->id }})"
                      class="px-3 py-1.5 rounded bg-gray-200 text-gray-800 text-xs">
                Deny
              </button>
              <button wire:click="sendReset({{ $u->id }})"
                      class="px-3 py-1.5 rounded bg-indigo-600 text-white text-xs">
                Send Password Reset
              </button>
            @else
              <button wire:click="approve({{ $u->id }})"
                      class="px-3 py-1.5 rounded bg-green-600 text-white text-xs">
                Approve
              </button>
            @endif
          </div>
        </article>
      @endforeach
    </div>

    <div class="mt-6">{{ $users->links() }}</div>
  @endif
</div>