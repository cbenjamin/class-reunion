<?php

namespace App\Livewire\Rsvp;

use App\Models\EventSetting;
use App\Models\Rsvp;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Form extends Component
{
    public bool $rsvpEnabled = false;

    public string $status = 'yes';
    public int $guest_count = 0;
    public ?string $note = null;

    public bool $saved = false;

    public function mount(): void
    {
        $settings = EventSetting::query()->first();
        $this->rsvpEnabled = (bool) ($settings?->rsvp_enabled ?? false);

        // If RSVP is disabled, don’t load anything else
        if (! $this->rsvpEnabled) return;

        // Load existing RSVP for this user (if any)
        $existing = Rsvp::where('user_id', Auth::id())->first();
        if ($existing) {
            $this->status = $existing->status;
            $this->guest_count = (int) $existing->guest_count;
            $this->note = $existing->note;
        }
    }

    protected function rules(): array
    {
        return [
            'status'      => ['required', 'in:yes,no,maybe'],
            'guest_count' => ['required', 'integer', 'min:0', 'max:10'],
            'note'        => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function save(): void
    {
        if (! $this->rsvpEnabled) {
            // Just in case someone posts while disabled
            return;
        }

        $data = $this->validate();
        $data['user_id'] = Auth::id();

        Rsvp::updateOrCreate(
            ['user_id' => Auth::id()],
            $data
        );

        $this->saved = true;
        session()->flash('status', 'RSVP saved!');
    }

    public function render()
    {
        return view('livewire.rsvp.form');
    }
}