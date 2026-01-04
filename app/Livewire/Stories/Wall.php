<?php

namespace App\Livewire\Stories;

use App\Models\Story;
use Livewire\Component;

class Wall extends Component
{
    public int $limit = 18;

    public function render()
    {
        $stories = Story::with('user')
            ->where('status','approved')
            ->orderByDesc('is_featured')
            ->latest()
            ->limit($this->limit)
            ->get();

        return view('livewire.stories.wall', [
            'stories' => $stories,
        ]);
    }
}