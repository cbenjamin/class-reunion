<div class="mt-8">
    <div class="bg-white shadow rounded-xl">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Recent Activity</h2>
        </div>

        @if($feed->isEmpty())
            <p class="px-6 py-8 text-sm text-gray-500 text-center">No activity yet — be the first to upload a photo or share a story!</p>
        @else
            <ul class="divide-y divide-gray-100">
                @foreach($feed as $item)
                    @php
                        $isAnonymous = (bool) $item->is_anonymous;
                        $actor = $isAnonymous ? 'A classmate' : ($userMap[$item->user_id] ?? 'Someone');
                        $time  = \Carbon\Carbon::parse($item->occurred_at)->diffForHumans();
                    @endphp

                    <li class="flex items-start gap-4 px-6 py-4">
                        {{-- Icon --}}
                        <div class="mt-0.5 flex-shrink-0">
                            @if($item->type === 'photo')
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-50 text-indigo-500">
                                    <i class="fa-solid fa-image text-sm"></i>
                                </span>
                            @elseif($item->type === 'story')
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-50 text-emerald-500">
                                    <i class="fa-solid fa-book-open text-sm"></i>
                                </span>
                            @else
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-50 text-amber-500">
                                    <i class="fa-solid fa-user-check text-sm"></i>
                                </span>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-800">
                                <span class="font-medium">{{ $actor }}</span>

                                @if($item->type === 'photo')
                                    shared a photo
                                    @if($item->excerpt)
                                        <span class="text-gray-500">— {{ Str::limit($item->excerpt, 60) }}</span>
                                    @endif
                                @elseif($item->type === 'story')
                                    shared a memory
                                    @if($item->excerpt)
                                        <span class="text-gray-500">— {{ Str::limit($item->excerpt, 80) }}</span>
                                    @endif
                                @else
                                    joined the class
                                @endif
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $time }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>

            @if($feed->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $feed->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
