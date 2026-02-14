<?php

namespace App\Livewire\Admin;

use App\Models\EventSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class EventSettings extends Component
{
    public ?int $id = null;

    public ?string $event_name = null;
    public ?string $event_date = null;   // bind as string (YYYY-MM-DD)
    public ?string $event_time = null;
    public ?string $venue = null;
    public ?string $address = null;
    public ?string $details = null;      // HTML from Quill
    public bool $rsvp_enabled = false;

    public function mount(): void
    {
        $s = EventSetting::query()->first();

        if ($s) {
            $this->id         = $s->id;
            $this->event_name = $s->event_name;
            $this->event_date = optional($s->event_date)->toDateString();
            $this->event_time = $s->event_time;
            $this->venue      = $s->venue;
            $this->address    = $s->address;
            $this->details    = $s->details;
            $this->rsvp_enabled = (bool) ($s->rsvp_enabled ?? false);
        }
    }

    public function rules(): array
    {
        return [
            'event_name'   => ['nullable', 'string', 'max:120'],
            'event_date'   => ['nullable', 'date'],
            'event_time'   => ['nullable', 'string', 'max:120'],
            'venue'        => ['nullable', 'string', 'max:160'],
            'address'      => ['nullable', 'string', 'max:240'],
            'details'      => ['nullable', 'string'],
            'rsvp_enabled'  => ['required', 'boolean'],
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        $s = $this->id
            ? EventSetting::findOrFail($this->id)
            : new EventSetting();

        $data['rsvp_enabled'] = (bool) $this->rsvp_enabled;

        $s->fill($data);
        $s->save();

        $this->id = $s->id;

        session()->flash('status', 'Event settings saved.');
        $this->dispatch('event-settings-updated');
    }

    public function render()
    {
        return view('livewire.admin.event-settings');
    }
}