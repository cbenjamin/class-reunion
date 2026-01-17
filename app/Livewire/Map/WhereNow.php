<?php

namespace App\Livewire\Map;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class WhereNow extends Component
{
    public array $markers = [];
    public array $topCities = [];

    public function mount(): void
    {
        // Only show users who opted in and have coords
        $rows = User::query()
            ->where('share_location', true)
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->get(['id','name','city','state','lat','lng']);

        $this->markers = $rows->map(fn($u) => [
            'lat' => (float) $u->lat,
            'lng' => (float) $u->lng,
            'label' => trim(($u->city ?: '').', '.($u->state ?: '')),
            'name' => $u->name,
        ])->values()->toArray();

        // Top cities (city/state aggregated)
        $this->topCities = User::query()
            ->selectRaw('city, state, count(*) as c')
            ->where('share_location', true)
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->groupBy('city','state')
            ->orderByDesc('c')
            ->limit(8)
            ->get()
            ->map(fn($r) => [
                'city' => (string) $r->city,
                'state' => (string) $r->state,
                'count' => (int) $r->c,
            ])->toArray();
    }

    public function render()
    {
        return view('livewire.map.where-now');
    }
}