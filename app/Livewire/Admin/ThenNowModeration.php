<?php

namespace App\Livewire\Admin;

use App\Models\ThenNow;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ThenNowModeration extends Component
{
    public $pending  = [];
    public $approved = [];

    public function mount(): void
    {
        abort_unless(Gate::allows('admin'), 403);
        $this->refreshLists();
    }

    public function refreshLists(): void
    {
        $this->pending  = ThenNow::with('user')->where('status', 'pending')->latest()->get();
        $this->approved = ThenNow::with('user')->where('status', 'approved')->latest('approved_at')->get();
    }

    public function approve(int $id): void
    {
        ThenNow::findOrFail($id)->update([
            'status'      => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        $this->refreshLists();
        session()->flash('status', 'Approved.');
    }

    public function reject(int $id): void
    {
        ThenNow::findOrFail($id)->update(['status' => 'rejected']);
        $this->refreshLists();
        session()->flash('status', 'Rejected.');
    }

    public function delete(int $id): void
    {
        $pair = ThenNow::findOrFail($id);

        foreach ([
            [$pair->then_disk, $pair->then_path],
            [$pair->now_disk,  $pair->now_path],
        ] as [$disk, $path]) {
            try {
                if (Storage::disk($disk)->exists($path)) {
                    Storage::disk($disk)->delete($path);
                }
            } catch (\Throwable $e) {
                Log::error('ThenNow file delete failed', ['id' => $id, 'path' => $path, 'error' => $e->getMessage()]);
            }
        }

        $pair->delete();
        $this->refreshLists();
        session()->flash('status', 'Deleted.');
    }

    public function render()
    {
        return view('livewire.admin.then-now-moderation');
    }
}
