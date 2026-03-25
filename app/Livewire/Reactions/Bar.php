<?php

namespace App\Livewire\Reactions;

use App\Actions\ToggleReaction;
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

        $result = (new ToggleReaction)->handle($this->photoId, Auth::id(), $type);

        if ($result['toggled_off']) {
            if (isset($this->counts[$type])) {
                $this->counts[$type] = max(0, $this->counts[$type] - 1);
                if ($this->counts[$type] === 0) unset($this->counts[$type]);
            }
            $this->mine = null;
        } else {
            $previous = $result['previous'];
            if ($previous && isset($this->counts[$previous])) {
                $this->counts[$previous] = max(0, $this->counts[$previous] - 1);
                if ($this->counts[$previous] === 0) unset($this->counts[$previous]);
            }
            $this->counts[$type] = ($this->counts[$type] ?? 0) + 1;
            $this->mine = $type;
        }
    }

    public function render()
    {
        return view('livewire.reactions.bar');
    }
}
