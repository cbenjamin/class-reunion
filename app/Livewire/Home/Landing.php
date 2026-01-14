<?php

namespace App\Livewire\Home;

use App\Models\EventSetting;
use App\Models\Photo;
use App\Models\PhotoReaction;
use App\Models\Story;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class Landing extends Component
{
    /** Event meta shown in hero + info blocks */
    public array $event = [];

    /** URL for hero image */
    public string $heroUrl = '';

    /** @var \Illuminate\Support\Collection<int,\App\Models\Photo> */
    public $photos;

    /** @var \Illuminate\Support\Collection<int,\App\Models\Story> */
    public $stories;

    /** Dashboard/guest stats */
    public array $stats = [
        'classmates' => 0,
        'photos'     => 0,
        'stories'    => 0,
    ];

    /** Reaction state keyed by photo_id */
    public array $reactionCounts = [];   // [photo_id => ['like'=>2,'love'=>1,...]]
    public array $myReactions   = [];    // [photo_id => 'like'|'love'|...]

    public function mount(): void
    {
        $s = EventSetting::query()->first();

        // Safer null handling for settings
        $this->event = [
            'name'    => $s?->event_name ?: config('app.name', 'Reunion'),
            'date'    => optional($s?->event_date)->format('F j, Y') ?: 'TBD',
            'time'    => $s?->event_time ?: 'TBD',
            'venue'   => $s?->venue ?: 'TBD',
            'address' => $s?->address ?: 'TBD',
            'notes'   => $s?->details ?: 'More details coming soon.',
        ];

        // Load content for both guests and members
        $this->refreshPhotos();   // also loads reactions + hero
        $this->refreshStories();  // approved stories for teasers
        $this->loadStats();       // counts for guest “How it works” section
    }

    /** Load approved photos + hero + reaction state */
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

        // Reaction counts for all listed photos
        $photoIds = $this->photos->pluck('id');

        $rows = PhotoReaction::whereIn('photo_id', $photoIds)
            ->select('photo_id', 'type', DB::raw('count(*) as c'))
            ->groupBy('photo_id', 'type')
            ->get();

        $this->reactionCounts = [];
        foreach ($rows as $r) {
            $this->reactionCounts[$r->photo_id][$r->type] = (int) $r->c;
        }

        // My reactions (if logged in)
        if (Auth::check()) {
            $mine = PhotoReaction::whereIn('photo_id', $photoIds)
                ->where('user_id', Auth::id())
                ->pluck('type', 'photo_id');
            $this->myReactions = $mine->toArray();
        } else {
            $this->myReactions = [];
        }
    }

    /** Load latest approved stories for homepage teasers */
    public function refreshStories(): void
    {
        $this->stories = Story::with('user')
            ->where('status', 'approved')
            ->latest()
            ->limit(18)
            ->get();
    }

    /** Simple site stats for guest section */
    public function loadStats(): void
    {
        $this->stats['classmates'] = User::where('is_approved', true)->count();
        $this->stats['photos']     = Photo::where('status', 'approved')->count();
        $this->stats['stories']    = Story::where('status', 'approved')->count();
    }

    /** Toggle or change a reaction inline (no full reload) */
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

        // Create or switch reaction atomically
        DB::transaction(function () use ($photoId, $type, $current) {
            $existing = PhotoReaction::where('photo_id', $photoId)
                ->where('user_id', Auth::id())
                ->first();

            if ($existing) {
                // decrement old type count
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
}