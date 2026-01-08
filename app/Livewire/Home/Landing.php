<?php

namespace App\Livewire\Home;

use App\Models\Photo;
use App\Models\PhotoReaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\EventSetting;

#[Layout('layouts.guest')]
class Landing extends Component
{
    public array $event = [];
    public string $heroUrl = '';
    /** @var \Illuminate\Support\Collection */
    public $photos;

    /** Reaction state keyed by photo_id */
    public array $reactionCounts = [];   // [photo_id => ['like' => 2, 'love' => 1, ...]]
    public array $myReactions   = [];    // [photo_id => 'like'|'love'|...]

    public function mount(): void
    {
        $s = EventSetting::query()->first();

        $this->event = [
            'name'    => $s->event_name ?: config('app.name', 'Reunion'),
            'date'    => optional($s?->event_date)->format('F j, Y') ?: 'TBD',
            'time'    => $s?->event_time ?: 'TBD',
            'venue'   => $s?->venue ?: 'TBD',
            'address' => $s?->address ?: 'TBD',
            'notes'   => $s?->details ?: 'More details coming soon.',
        ];

        $this->refreshPhotos(); // also loads reactions + hero
    }

    public function refreshPhotos(): void
    {
        $this->photos = Photo::where('status', 'approved')
            ->orderByDesc('is_featured')
            ->latest()
            ->limit(60)
            ->get();

        // Hero image
        $picked = $this->photos->firstWhere('is_featured', true) ?? $this->photos->first();
        $this->heroUrl = $picked ? Storage::disk($picked->disk)->url($picked->path) : '';

        // Load reaction counts for all listed photos
        $photoIds = $this->photos->pluck('id');
        $rows = PhotoReaction::whereIn('photo_id', $photoIds)
            ->select('photo_id','type', DB::raw('count(*) as c'))
            ->groupBy('photo_id','type')
            ->get();

        $this->reactionCounts = [];
        foreach ($rows as $r) {
            $this->reactionCounts[$r->photo_id][$r->type] = (int) $r->c;
        }

        // Load my reactions (if logged in)
        if (Auth::check()) {
            $mine = PhotoReaction::whereIn('photo_id', $photoIds)
                ->where('user_id', Auth::id())
                ->pluck('type', 'photo_id');
            $this->myReactions = $mine->toArray();
        } else {
            $this->myReactions = [];
        }
    }

    public function react(int $photoId, string $type)
    {
        if (! Auth::check()) {
            return $this->redirectRoute('login', navigate: true);
        }

        $current = $this->myReactions[$photoId] ?? null;

        // Toggle off if same type
        if ($current === $type) {
            PhotoReaction::where('photo_id', $photoId)
                ->where('user_id', Auth::id())
                ->delete();

            if (isset($this->reactionCounts[$photoId][$type])) {
                $this->reactionCounts[$photoId][$type] = max(0, $this->reactionCounts[$photoId][$type] - 1);
                if ($this->reactionCounts[$photoId][$type] === 0) {
                    unset($this->reactionCounts[$photoId][$type]);
                }
            }
            unset($this->myReactions[$photoId]);
            return;
        }

        // Create or change reaction atomically
        DB::transaction(function () use ($photoId, $type, $current) {
            $existing = PhotoReaction::where('photo_id', $photoId)
                ->where('user_id', Auth::id())
                ->first();

            if ($existing) {
                // decrement old type
                if ($current && isset($this->reactionCounts[$photoId][$current])) {
                    $this->reactionCounts[$photoId][$current] = max(0, $this->reactionCounts[$photoId][$current] - 1);
                    if ($this->reactionCounts[$photoId][$current] === 0) {
                        unset($this->reactionCounts[$photoId][$current]);
                    }
                }
                $existing->update(['type' => $type]);
            } else {
                PhotoReaction::create([
                    'photo_id' => $photoId,
                    'user_id'  => Auth::id(),
                    'type'     => $type,
                ]);
            }

            // increment new type
            $this->reactionCounts[$photoId][$type] = ($this->reactionCounts[$photoId][$type] ?? 0) + 1;
            $this->myReactions[$photoId] = $type;
        });
    }

    public function render()
    {
        return view('livewire.home.landing');
    }

    public int $clicks = 0;
    public function ping() { $this->clicks++; }
}