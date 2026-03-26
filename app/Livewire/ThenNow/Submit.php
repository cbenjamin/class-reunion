<?php

namespace App\Livewire\ThenNow;

use App\Models\ThenNow;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Submit extends Component
{
    use WithFileUploads, AuthorizesRequests;

    #[Validate('required|image|max:8192')] public $thenPhoto;
    #[Validate('required|image|max:8192')] public $nowPhoto;
    #[Validate('nullable|string|max:200')] public ?string $caption = null;

    public ?ThenNow $existing = null;

    public function mount(): void
    {
        $this->existing = auth()->user()->thenNow;
    }

    public function save(): void
    {
        $this->validate();

        $thenPath = $this->thenPhoto->store('reunion/then-now', 'public');
        $nowPath  = $this->nowPhoto->store('reunion/then-now', 'public');

        // Delete old files if replacing an existing submission
        if ($this->existing) {
            foreach ([
                [$this->existing->then_disk, $this->existing->then_path],
                [$this->existing->now_disk,  $this->existing->now_path],
            ] as [$disk, $path]) {
                try {
                    if (Storage::disk($disk)->exists($path)) {
                        Storage::disk($disk)->delete($path);
                    }
                } catch (\Throwable) {}
            }
        }

        ThenNow::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'then_disk'   => 'public',
                'then_path'   => $thenPath,
                'now_disk'    => 'public',
                'now_path'    => $nowPath,
                'caption'     => $this->caption,
                'status'      => 'pending',
                'approved_by' => null,
                'approved_at' => null,
            ]
        );

        $this->reset(['thenPhoto', 'nowPhoto', 'caption']);
        $this->existing = auth()->user()->fresh()->thenNow;
        session()->flash('status', 'Your Then & Now is pending approval!');
    }

    public function delete(): void
    {
        if (! $this->existing) return;

        $this->authorize('delete', $this->existing);

        foreach ([
            [$this->existing->then_disk, $this->existing->then_path],
            [$this->existing->now_disk,  $this->existing->now_path],
        ] as [$disk, $path]) {
            try {
                if (Storage::disk($disk)->exists($path)) {
                    Storage::disk($disk)->delete($path);
                }
            } catch (\Throwable) {}
        }

        $this->existing->delete();
        $this->existing = null;
        session()->flash('status', 'Your Then & Now has been removed.');
    }

    public function render()
    {
        return view('livewire.then-now.submit');
    }
}
