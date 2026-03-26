<div class="max-w-6xl mx-auto px-4 py-10">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold">Then &amp; Now</h1>
            <p class="text-sm text-gray-500 mt-1">See how your classmates have changed over the years.</p>
        </div>
        <a href="{{ route('then-now.submit') }}" class="btn btn-primary">
            <i class="fa-solid fa-upload mr-1"></i> Add Mine
        </a>
    </div>

    @if($pairs->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <i class="fa-solid fa-clock-rotate-left text-4xl mb-4"></i>
            <p class="text-lg font-medium">No submissions yet</p>
            <p class="text-sm mt-1">Be the first to share your Then &amp; Now!</p>
            <a href="{{ route('then-now.submit') }}" class="btn btn-primary mt-4 inline-block">Add Mine</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($pairs as $pair)
                <div class="bg-white rounded-2xl shadow ring-1 ring-black/5 overflow-hidden">
                    {{-- Side-by-side photos --}}
                    <div class="grid grid-cols-2">
                        <div class="relative">
                            <img src="{{ Storage::disk($pair->then_disk)->url($pair->then_path) }}"
                                 class="w-full h-48 object-cover"
                                 alt="Then — {{ $pair->user->name }}">
                            <span class="absolute bottom-2 left-2 text-[11px] font-semibold text-white bg-black/50 rounded px-1.5 py-0.5">Then</span>
                        </div>
                        <div class="relative border-l-2 border-white">
                            <img src="{{ Storage::disk($pair->now_disk)->url($pair->now_path) }}"
                                 class="w-full h-48 object-cover"
                                 alt="Now — {{ $pair->user->name }}">
                            <span class="absolute bottom-2 right-2 text-[11px] font-semibold text-white bg-black/50 rounded px-1.5 py-0.5">Now</span>
                        </div>
                    </div>

                    {{-- Name + caption --}}
                    <div class="px-4 py-3">
                        <p class="font-semibold text-sm text-gray-900">{{ $pair->user->name }}</p>
                        @if($pair->caption)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $pair->caption }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($pairs->hasPages())
            <div class="mt-8">{{ $pairs->links() }}</div>
        @endif
    @endif
</div>
