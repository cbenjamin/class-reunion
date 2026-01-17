<?php

namespace App\Livewire\Home;

use App\Models\Photo;
use App\Models\PhotoReaction;
use App\Models\User;
use App\Models\Story;
use App\Models\EventSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class Landing extends Component
{
    public array $event = [];
    public string $heroUrl = '';

    /** @var \Illuminate\Support\Collection */
    public $photos;

    /** Reactions (photo_id keyed) */
    public array $reactionCounts = [];
    public array $myReactions   = [];

    public array $stats = [
        'classmates' => 0,
        'photos'     => 0,
        'stories'    => 0,
    ];

    /** Map data for homepage */
    public array $mapMarkers = [];  // [{lat,lng,label,name}]
    public array $topCities  = [];  // [{city,state,count}]

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

        $this->computeStats();
        $this->refreshPhotos();
        $this->loadMapData(); // ← add map markers for homepage
    }

    protected function computeStats(): void
    {
        $this->stats = [
            'classmates' => User::where('is_approved', true)->count(),
            'photos'     => Photo::where('status', 'approved')->count(),
            'stories'    => Story::where('status', 'approved')->count(),
        ];
    }
    
    public function refreshPhotos(): void
    {
        $this->photos = Photo::where('status', 'approved')
            ->orderByDesc('is_featured')
            ->latest()
            ->limit(60)
            ->get();

        $picked = $this->photos->firstWhere('is_featured', true) ?? $this->photos->first();
        $this->heroUrl = $picked ? Storage::disk($picked->disk)->url($picked->path) : '';

        $photoIds = $this->photos->pluck('id');

        $rows = PhotoReaction::whereIn('photo_id', $photoIds)
            ->select('photo_id','type', DB::raw('count(*) as c'))
            ->groupBy('photo_id','type')
            ->get();

        $this->reactionCounts = [];
        foreach ($rows as $r) {
            $this->reactionCounts[$r->photo_id][$r->type] = (int) $r->c;
        }

        if (Auth::check()) {
            $mine = PhotoReaction::whereIn('photo_id', $photoIds)
                ->where('user_id', Auth::id())
                ->pluck('type', 'photo_id');
            $this->myReactions = $mine->toArray();
        } else {
            $this->myReactions = [];
        }
    }

    protected function loadMapData(): void
    {
        // Only classmates who opted in + have coords
        $rows = User::query()
            ->where('share_location', true)
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->get(['id','name','city','state','lat','lng']);

        $this->mapMarkers = $rows->map(fn($u) => [
            'lat'   => (float) $u->lat,
            'lng'   => (float) $u->lng,
            'label' => trim(($u->city ?: '').', '.($u->state ?: '')),
            'name'  => $u->name,
        ])->values()->toArray();

        $this->topCities = User::query()
            ->selectRaw('city, state, count(*) as c')
            ->where('share_location', true)
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->groupBy('city','state')
            ->orderByDesc('c')
            ->limit(6)
            ->get()
            ->map(fn($r) => [
                'city'  => (string) $r->city,
                'state' => (string) $r->state,
                'count' => (int) $r->c,
            ])->toArray();
    }

    /** Reaction handler kept as-is */
    public function react(int $photoId, string $type)
    {
        if (! Auth::check()) {
            return $this->redirectRoute('login', navigate: true);
        }

        $current = $this->myReactions[$photoId] ?? null;

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

        DB::transaction(function () use ($photoId, $type, $current) {
            $existing = PhotoReaction::where('photo_id', $photoId)
                ->where('user_id', Auth::id())
                ->first();

            if ($existing) {
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