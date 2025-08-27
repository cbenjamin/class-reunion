<?php

namespace App\Livewire\Reactions;

use App\Models\Photo;
use App\Models\PhotoReaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Bar extends Component
{
    public int $photoId;

    /** @var array<string,int> */
    public array $counts = [];

    /** @var string[] */
    public array $emoji = [
        'like'  => '👍',
        'love'  => '❤️',
        'laugh' => '😂',
        'wow'   => '😮',
        'sad'   => '😢',
        'party' => '🎉',
    ];

    public ?string $mine = null;

    // Accept a photo ID (from parent)
    public function mount(int $photo): void
    {
        $this->photoId = $photo;
        $this->refreshCounts();
        $this->mine = Auth::check()
            ? PhotoReaction::where('photo_id', $this->photoId)
                ->where('user_id', Auth::id())
                ->value('type')
            : null;
    }

    public function refreshCounts(): void
    {
        $this->counts = PhotoReaction::where('photo_id', $this->photoId)
            ->select('type', DB::raw('count(*) as c'))
            ->groupBy('type')
            ->pluck('c', 'type')
            ->all();
    }

    public function react(string $type)
    {
        if (! Auth::check()) {
            return $this->redirectRoute('login', navigate: true);
        }

        // toggle off if clicking the same reaction
        if ($this->mine === $type) {
            PhotoReaction::where('photo_id', $this->photoId)
                ->where('user_id', Auth::id())
                ->delete();

            if (isset($this->counts[$type])) {
                $this->counts[$type] = max(0, $this->counts[$type] - 1);
                if ($this->counts[$type] === 0) unset($this->counts[$type]);
            }
            $this->mine = null;
            return;
        }

        DB::transaction(function () use ($type) {
            $existing = PhotoReaction::where('photo_id', $this->photoId)
                ->where('user_id', Auth::id())
                ->first();

            if ($existing) {
                if (isset($this->counts[$existing->type])) {
                    $this->counts[$existing->type] = max(0, $this->counts[$existing->type] - 1);
                    if ($this->counts[$existing->type] === 0) unset($this->counts[$existing->type]);
                }
                $existing->update(['type' => $type]);
            } else {
                PhotoReaction::create([
                    'photo_id' => $this->photoId,
                    'user_id'  => Auth::id(),
                    'type'     => $type,
                ]);
            }

            $this->counts[$type] = ($this->counts[$type] ?? 0) + 1;
            $this->mine = $type;
        });
    }

    public function render()
    {
        return view('livewire.reactions.bar');
    }
}