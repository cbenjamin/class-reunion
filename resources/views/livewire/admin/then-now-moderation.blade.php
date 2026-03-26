<div class="max-w-5xl mx-auto px-4 py-10">

    <h1 class="text-2xl font-bold mb-8">Then &amp; Now — Moderation</h1>

    @if(session('status'))
        <div class="mb-6 rounded-lg bg-green-50 text-green-800 px-4 py-3 text-sm">{{ session('status') }}</div>
    @endif

    {{-- Pending --}}
    <section class="mb-12">
        <h2 class="text-lg font-semibold mb-4">Pending <span class="text-gray-400 font-normal text-base">({{ count($pending) }})</span></h2>

        @forelse($pending as $pair)
            <div class="bg-white rounded-xl shadow ring-1 ring-black/5 overflow-hidden mb-4">
                <div class="grid grid-cols-2">
                    <div class="relative">
                        <img src="{{ Storage::disk($pair->then_disk)->url($pair->then_path) }}"
                             class="w-full h-52 object-cover" alt="Then">
                        <span class="absolute bottom-2 left-2 text-[11px] font-semibold text-white bg-black/50 rounded px-1.5 py-0.5">Then</span>
                    </div>
                    <div class="relative border-l-2 border-white">
                        <img src="{{ Storage::disk($pair->now_disk)->url($pair->now_path) }}"
                             class="w-full h-52 object-cover" alt="Now">
                        <span class="absolute bottom-2 right-2 text-[11px] font-semibold text-white bg-black/50 rounded px-1.5 py-0.5">Now</span>
                    </div>
                </div>
                <div class="px-5 py-4 flex items-center justify-between gap-4">
                    <div>
                        <p class="font-medium text-sm">{{ $pair->user->name }}</p>
                        @if($pair->caption)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $pair->caption }}</p>
                        @endif
                        <p class="text-xs text-gray-400 mt-1">Submitted {{ $pair->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex gap-2 flex-shrink-0">
                        <button wire:click="approve({{ $pair->id }})" class="btn btn-primary text-sm">Approve</button>
                        <button wire:click="reject({{ $pair->id }})" class="btn btn-secondary text-sm">Reject</button>
                        <button wire:click="delete({{ $pair->id }})" wire:confirm="Permanently delete this submission?"
                                class="btn btn-danger text-sm">Delete</button>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-500">No pending submissions.</p>
        @endforelse
    </section>

    {{-- Approved --}}
    <section>
        <h2 class="text-lg font-semibold mb-4">Approved <span class="text-gray-400 font-normal text-base">({{ count($approved) }})</span></h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($approved as $pair)
                <div class="bg-white rounded-xl shadow ring-1 ring-black/5 overflow-hidden">
                    <div class="grid grid-cols-2">
                        <div class="relative">
                            <img src="{{ Storage::disk($pair->then_disk)->url($pair->then_path) }}"
                                 class="w-full h-36 object-cover" alt="Then">
                            <span class="absolute bottom-1 left-1 text-[10px] font-semibold text-white bg-black/50 rounded px-1 py-0.5">Then</span>
                        </div>
                        <div class="relative border-l-2 border-white">
                            <img src="{{ Storage::disk($pair->now_disk)->url($pair->now_path) }}"
                                 class="w-full h-36 object-cover" alt="Now">
                            <span class="absolute bottom-1 right-1 text-[10px] font-semibold text-white bg-black/50 rounded px-1 py-0.5">Now</span>
                        </div>
                    </div>
                    <div class="px-3 py-3 flex items-center justify-between gap-2">
                        <p class="text-sm font-medium truncate">{{ $pair->user->name }}</p>
                        <div class="flex gap-1 flex-shrink-0">
                            <button wire:click="reject({{ $pair->id }})" class="btn btn-secondary text-xs">Reject</button>
                            <button wire:click="delete({{ $pair->id }})" wire:confirm="Permanently delete this?"
                                    class="btn btn-danger text-xs">Delete</button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500 col-span-3">Nothing approved yet.</p>
            @endforelse
        </div>
    </section>
</div>
