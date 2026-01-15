<div class="p-6">
  <h1 class="text-2xl font-semibold mb-4">Memorials Moderation</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- PENDING --}}
    <section>
      <h2 class="font-medium text-gray-700 mb-2">Pending</h2>
      <div class="space-y-3">
        @forelse($pending as $m)
          @php $url = $m->photo_path ? Storage::disk($m->disk)->url($m->photo_path) : null; @endphp
          <article class="rounded-xl bg-white shadow ring-1 ring-black/5 p-4">
            <div class="flex items-start gap-3">
              <div class="w-20 h-20 bg-gray-100 rounded overflow-hidden">
                @if($url)
                  <img class="w-full h-full object-cover" src="{{ $url }}" alt="">
                @endif
              </div>
              <div class="flex-1">
                <div class="font-semibold">
                  {{ $m->classmate_name }}
                  @if($m->graduation_year)
                    <span class="text-gray-500 font-normal">• {{ $m->graduation_year }}</span>
                  @endif
                </div>

                @if($m->bio)
                  <p class="text-sm text-gray-700 mt-1">{{ Str::limit($m->bio, 140) }}</p>
                @endif

                <div class="mt-2 text-xs text-gray-500 space-x-1">
                  @if($m->submitter_name) <span>From: {{ $m->submitter_name }}</span>@endif
                  @if($m->submitter_email) <span>• {{ $m->submitter_email }}</span>@endif
                  @if($m->relationship)    <span>• {{ $m->relationship }}</span>@endif
                  @if($m->obituary_url)    <span>• <a href="{{ $m->obituary_url }}" class="underline" target="_blank" rel="noopener">Obituary</a></span>@endif
                </div>

                <div class="mt-3 flex items-center gap-2">
                  <button wire:click="approve({{ $m->id }})" class="btn btn-primary">Approve</button>
                  <button wire:click="reject({{ $m->id }})" class="btn btn-danger">Reject</button>
                </div>
              </div>
            </div>
          </article>
        @empty
          <div class="text-sm text-gray-500">No pending memorials.</div>
        @endforelse
      </div>
    </section>

    {{-- APPROVED --}}
    <section>
      <h2 class="font-medium text-gray-700 mb-2">Approved</h2>
      <div class="space-y-3">
        @forelse($approved as $m)
          @php $url = $m->photo_path ? Storage::disk($m->disk)->url($m->photo_path) : null; @endphp
          <article class="rounded-xl bg-white shadow ring-1 ring-black/5 p-4">
            <div class="flex items-start gap-3">
              <div class="w-20 h-20 bg-gray-100 rounded overflow-hidden">
                @if($url)
                  <img class="w-full h-full object-cover" src="{{ $url }}" alt="">
                @endif
              </div>
              <div class="flex-1">
                <div class="font-semibold">
                  {{ $m->classmate_name }}
                  @if($m->graduation_year)
                    <span class="text-gray-500 font-normal">• {{ $m->graduation_year }}</span>
                  @endif
                </div>

                <div class="mt-3 flex items-center gap-2">
                  @if(!$m->is_featured)
                    <button wire:click="feature({{ $m->id }})" class="btn btn-secondary">Feature</button>
                  @else
                    <button wire:click="unfeature({{ $m->id }})" class="btn btn-secondary">Unfeature</button>
                  @endif
                  <button wire:click="reject({{ $m->id }})" class="btn btn-danger">Reject</button>
                </div>
              </div>
            </div>
          </article>
        @empty
          <div class="text-sm text-gray-500">No approved memorials yet.</div>
        @endforelse
      </div>
    </section>
  </div>
</div>