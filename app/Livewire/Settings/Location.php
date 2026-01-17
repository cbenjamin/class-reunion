<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Location extends Component
{
    public string $city = '';
    public string $state = '';
    public bool $share_location = true;
    public ?float $lat = null;
    public ?float $lng = null;

    public function mount(): void
    {
        $u = Auth::user();
        $this->city = (string) ($u->city ?? '');
        $this->state = (string) ($u->state ?? '');
        $this->share_location = (bool) ($u->share_location ?? true);
        $this->lat = $u->lat;
        $this->lng = $u->lng;
    }

    public function save(): void
    {
        $data = $this->validate([
            'city'  => ['required','string','max:120'],
            'state' => ['required','string','max:120'],
            'share_location' => ['boolean'],
        ]);

        // Geocode city/state -> approximate centroid
        [$lat, $lng] = $this->geocodeCityState($this->city, $this->state);

        Auth::user()->update([
            'city'  => $this->city,
            'state' => $this->state,
            'share_location' => $this->share_location,
            'lat'   => $lat,
            'lng'   => $lng,
        ]);

        $this->lat = $lat;
        $this->lng = $lng;

        session()->flash('status', 'Location updated.');
        $this->dispatch('refocus-map'); // optional hook for the map preview
    }

    /** @return array{0: ?float, 1: ?float} */
    protected function geocodeCityState(string $city, string $state): array
    {
        $q = trim("$city, $state");
        if ($q === ',') return [null, null];

        // Cache for 1 year to be kind to Nominatim
        $key = 'geo:'.md5(strtolower($q));
        return Cache::remember($key, now()->addYear(), function () use ($q) {
            try {
                $ua = config('app.url', 'http://localhost'). ' ReunionApp/1.0';
                $resp = Http::retry(2, 500)
                    ->withHeaders(['User-Agent' => $ua])
                    ->get('https://nominatim.openstreetmap.org/search', [
                        'format' => 'json',
                        'q'      => $q,
                        'limit'  => 1,
                    ]);

                if (!$resp->ok() || empty($resp[0])) {
                    return [null, null];
                }
                $lat = isset($resp[0]['lat']) ? (float) $resp[0]['lat'] : null;
                $lng = isset($resp[0]['lon']) ? (float) $resp[0]['lon'] : null;

                // Round to ~ city precision to preserve privacy a bit
                return [$lat ? round($lat, 4) : null, $lng ? round($lng, 4) : null];
            } catch (\Throwable $e) {
                return [null, null];
            }
        });
    }

    public function render()
    {
        return view('livewire.settings.location');
    }
}