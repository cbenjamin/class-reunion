<?php

namespace App\Livewire\Dashboard;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\EventSetting;

#[Layout('layouts.app')]
class Index extends Component
{
    public array $event = [];
    public string $mapUrl = '#';

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
        //$this->mapUrl = "https://www.google.com/maps/search/?api=1&query={$query}";
    }

    public function render()
    {
        return view('livewire.dashboard.index');
    }
}