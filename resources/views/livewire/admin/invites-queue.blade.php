
  <div class="max-w-6xl mx-auto px-4 py-10 space-y-8">
    @if(session('status'))
      <div class="rounded bg-green-50 text-green-800 px-4 py-3 text-sm">{{ session('status') }}</div>
    @endif
    <h1 class="text-2xl font-bold">Invitation Requests</h1>

    <div class="grid md:grid-cols-3 gap-6">
      <div>
        <h2 class="font-semibold mb-2">Pending</h2>
        <div class="space-y-3">
          @forelse($pending as $r)
            <div class="rounded-xl border p-3 text-sm">
              <div class="font-medium">{{ $r->full_name }}</div>
              <div class="text-gray-600">{{ $r->email }} • {{ $r->grad_year }}</div>
              @if($r->answers)
              <div class="text-xs text-gray-500 mt-1">
                <div><strong>Maiden:</strong> {{ $r->answers['maiden_name'] ?? '—' }}</div>
                <div><strong>Homeroom:</strong> {{ $r->answers['homeroom'] ?? '—' }}</div>
                <div><strong>Fun:</strong> {{ $r->answers['interest'] ?? '—' }}</div>
              </div>
              @endif
              <div class="flex gap-2 mt-3">
                <button wire:click="approve({{ $r->id }})" class="px-3 py-1.5 rounded bg-green-600 text-white text-xs">Approve</button>
                <button wire:click="deny({{ $r->id }})" class="px-3 py-1.5 rounded bg-red-600 text-white text-xs">Deny</button>
              </div>
            </div>
          @empty
            <p class="text-sm text-gray-600">No pending requests.</p>
          @endforelse
        </div>
      </div>

      <div>
        <h2 class="font-semibold mb-2">Approved</h2>
        <ul class="space-y-2 text-sm">
          @forelse($approved as $r)
            <li class="border rounded px-3 py-2">{{ $r->full_name }} — {{ $r->email }}</li>
          @empty
            <li class="text-gray-600 text-sm">None</li>
          @endforelse
        </ul>
      </div>

      <div>
        <h2 class="font-semibold mb-2">Denied</h2>
        <ul class="space-y-2 text-sm">
          @forelse($denied as $r)
            <li class="border rounded px-3 py-2">{{ $r->full_name }} — {{ $r->email }}</li>
          @empty
            <li class="text-gray-600 text-sm">None</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
