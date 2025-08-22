<?php

namespace App\Livewire\Home;

use App\Models\Photo;
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

    public function mount(): void
    {
        $this->event = config('reunion') ?? [
            'name'    => config('app.name', 'Reunion'),
            'date'    => 'TBD',
            'time'    => '',
            'venue'   => '',
            'address' => '',
            'notes'   => '',
        ];

        $this->refreshPhotos(); // also sets $heroUrl
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
    }

    public function render()
    {
        return view('livewire.home.landing');
    }
}