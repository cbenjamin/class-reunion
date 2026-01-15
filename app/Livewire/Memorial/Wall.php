<?php

namespace App\Livewire\Memorial;

use App\Models\Memorial;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class Wall extends Component
{
    public $q = '';

    public function updatedQ(): void
    {
        // trigger re-render on search
    }

    public function render()
    {
        $query = Memorial::query()->where('status','approved')
            ->when($this->q, fn($q) =>
                $q->where(function($w){
                    $w->where('classmate_name','like','%'.$this->q.'%')
                      ->orWhere('graduation_year','like','%'.$this->q.'%');
                })
            )
            ->orderByDesc('is_featured')
            ->latest();

        $memorials = $query->paginate(18);

        return view('livewire.memorial.wall', compact('memorials'));
    }
}