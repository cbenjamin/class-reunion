<div class="mx-auto max-w-6xl px-5 py-8">
  <div class="flex items-start justify-between gap-4 mb-6">
    <div>
      <h1 class="text-2xl font-semibold">RSVPs</h1>
      <p class="text-sm text-gray-600">See who’s coming and how many guests.</p>
    </div>
  </div>

  {{-- Totals --}}
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="card text-center">
      <div class="text-2xl font-semibold tabular-nums">{{ number_format($totals['all']) }}</div>
      <div class="text-xs text-gray-500 mt-1">Total</div>
    </div>
    <div class="card text-center">
      <div class="text-2xl font-semibold tabular-nums">{{ number_format($totals['yes']) }}</div>
      <div class="text-xs text-gray-500 mt-1">Yes</div>
    </div>
    <div class="card text-center">
      <div class="text-2xl font-semibold tabular-nums">{{ number_format($totals['maybe']) }}</div>
      <div class="text-xs text-gray-500 mt-1">Maybe</div>
    </div>
    <div class="card text-center">
      <div class="text-2xl font-semibold tabular-nums">{{ number_format($totals['no']) }}</div>
      <div class="text-xs text-gray-500 mt-1">No</div>
    </div>
  </div>

  {{-- Filters --}}
  <div class="card mb-6">
    <div class="grid sm:grid-cols-3 gap-4 items-end">
      <div>
        <label class="label">Status</label>
        <select class="field" wire:model.live="status">
          <option value="all">All</option>
          <option value="yes">Yes</option>
          <option value="maybe">Maybe</option>
          <option value="no">No</option>
        </select>
      </div>

      <div class="sm:col-span-2">
        <label class="label">Search</label>
        <input class="field" type="text" wire:model.live.debounce.300ms="search" placeholder="Name, email, or note…">
      </div>
    </div>
  </div>

  {{-- Table --}}
  <div class="rounded-xl bg-white shadow ring-1 ring-black/5 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-gray-700">
          <tr>
            <th class="text-left px-4 py-3 font-medium cursor-pointer select-none" wire:click="sortBy('status')">
              Status
              @if($sortField === 'status') <span class="text-xs text-gray-400">{{ $sortDir === 'asc' ? '▲' : '▼' }}</span> @endif
            </th>
            <th class="text-left px-4 py-3 font-medium">Name</th>
            <th class="text-left px-4 py-3 font-medium">Email</th>
            <th class="text-left px-4 py-3 font-medium cursor-pointer select-none" wire:click="sortBy('guest_count')">
              Guests
              @if($sortField === 'guest_count') <span class="text-xs text-gray-400">{{ $sortDir === 'asc' ? '▲' : '▼' }}</span> @endif
            </th>
            <th class="text-left px-4 py-3 font-medium">Note</th>
            <th class="text-left px-4 py-3 font-medium cursor-pointer select-none" wire:click="sortBy('updated_at')">
              Updated
              @if($sortField === 'updated_at') <span class="text-xs text-gray-400">{{ $sortDir === 'asc' ? '▲' : '▼' }}</span> @endif
            </th>
          </tr>
        </thead>

        <tbody class="divide-y">
          @forelse($rsvps as $r)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3">
                @php
                  $pill = match($r->status) {
                    'yes' => 'bg-green-50 text-green-700 ring-green-200',
                    'maybe' => 'bg-yellow-50 text-yellow-700 ring-yellow-200',
                    default => 'bg-red-50 text-red-700 ring-red-200',
                  };
                @endphp
                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 {{ $pill }}">
                  {{ strtoupper($r->status) }}
                </span>
              </td>

              <td class="px-4 py-3 font-medium text-gray-900">
                {{ $r->user->name ?? '—' }}
              </td>

              <td class="px-4 py-3 text-gray-700">
                {{ $r->user->email ?? '—' }}
              </td>

              <td class="px-4 py-3 text-gray-700 tabular-nums">
                {{ (int) $r->guest_count }}
              </td>

              <td class="px-4 py-3 text-gray-700 max-w-md">
                <div class="line-clamp-2">{{ $r->note ?: '—' }}</div>
              </td>

              <td class="px-4 py-3 text-gray-600">
                {{ $r->updated_at?->format('M j, Y g:ia') }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-4 py-10 text-center text-gray-500">
                No RSVPs found.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="px-4 py-3 bg-white border-t">
      {{ $rsvps->links() }}
    </div>
  </div>
</div>