<?php

namespace App\Livewire\Dashboard;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public array $event = [];
    public string $mapUrl = '#';

    public function mount(): void
    {
        $this->event = config('reunion');

        $venue  = $this->event['venue']  ?? '';
        $addr   = $this->event['address'] ?? '';
        $query  = urlencode(trim("$venue, $addr"));
        $this->mapUrl = "https://www.google.com/maps/search/?api=1&query={$query}";
    }

    public function render()
    {
        return view('livewire.dashboard.index');
    }
}