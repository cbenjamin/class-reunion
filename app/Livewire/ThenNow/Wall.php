<?php

namespace App\Livewire\ThenNow;

use App\Models\ThenNow;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Wall extends Component
{
    use WithPagination;

    public function render()
    {
        $pairs = ThenNow::with('user')
            ->where('status', 'approved')
            ->latest('approved_at')
            ->paginate(18);

        return view('livewire.then-now.wall', compact('pairs'));
    }
}
