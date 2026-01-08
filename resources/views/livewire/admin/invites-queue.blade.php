<div class="max-w-6xl mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-6">Pending Invitations</h1>

  @if(session('status'))
    <div class="mb-4 rounded bg-green-50 text-green-800 px-4 py-3 text-sm">{{ session('status') }}</div>
  @endif

  @if($pending->isEmpty())
    <p class="text-sm text-gray-600">No pending requests.</p>
  @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      @foreach($pending as $req)
        <article class="bg-white rounded-xl shadow p-4">
          <div class="font-semibold">{{ $req->name ?? $req->full_name ?? 'Unknown' }}</div>
          <div class="text-sm text-gray-600">{{ $req->email }}</div>
          <div class="mt-2 text-xs text-gray-500">Requested: {{ $req->created_at?->format('M j, Y g:ia') }}</div>

          {{-- If you store Q&A, show a tiny peek here --}}
          @php
            // Pull the two fields (support a couple common key spellings)
            $maiden   = trim((string) (data_get($req->answers, 'maiden_name')
                        ?? data_get($req->answers, 'maidenName') ?? ''));
            $interest = trim((string) (data_get($req->answers, 'interest')
                        ?? data_get($req->answers, 'interests') ?? ''));
          @endphp

          @if($maiden || $interest)
            <div class="mt-3 flex flex-wrap gap-2 text-xs">
              @if($maiden)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-gray-100 text-gray-800">
                  👤 <span class="opacity-70">Maiden name:</span> <span class="font-medium">{{ $maiden }}</span>
                </span>
              @endif
              @if($interest)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-gray-100 text-gray-800">
                  ⭐ <span class="opacity-70">Interest:</span> <span class="font-medium">{{ $interest }}</span>
                </span>
              @endif
            </div>
          @else
            <div class="mt-3 text-xs text-gray-500">
              No profile details provided.
            </div>
          @endif

          <div class="mt-4 flex gap-2">
            <button wire:click="approve({{ $req->id }})"
                    class="btn btn-primary px-3 py-1.5 text-xs">Approve</button>

            <button onclick="if(!confirm('Reject this request?')) return false;"
                    wire:click="reject({{ $req->id }})"
                    class="btn btn-danger px-3 py-1.5 text-xs">Reject</button>
          </div>
        </article>
      @endforeach
    </div>

    <div class="mt-6">{{ $pending->links() }}</div>
  @endif
</div>