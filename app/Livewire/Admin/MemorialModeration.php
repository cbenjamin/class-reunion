<?php

namespace App\Livewire\Admin;

use App\Models\Memorial;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class MemorialModeration extends Component
{
    public function mount(): void
    {
        abort_unless(Gate::allows('admin'), 403);
    }

    public function approve(int $id): void
    {
        $m = Memorial::findOrFail($id);
        $m->status = 'approved';
        $m->approved_by = auth()->id();
        $m->approved_at = now();
        $m->save();

        $this->dispatch('notify', message: 'Approved');
    }

    public function reject(int $id): void
    {
        $m = Memorial::findOrFail($id);
        $m->status = 'rejected';
        $m->is_featured = false;
        $m->save();

        $this->dispatch('notify', message: 'Rejected');
    }

    public function feature(int $id): void
    {
        $m = Memorial::findOrFail($id);
        if ($m->status !== 'approved') return;
        $m->is_featured = true;
        $m->save();
        $this->dispatch('notify', message: 'Featured');
    }

    public function unfeature(int $id): void
    {
        $m = Memorial::findOrFail($id);
        $m->is_featured = false;
        $m->save();
        $this->dispatch('notify', message: 'Unfeatured');
    }

    public function render()
    {
        $pending  = Memorial::where('status','pending')->latest()->get();
        $approved = Memorial::where('status','approved')->latest()->get();

        return view('livewire.admin.memorial-moderation', compact('pending','approved'));
    }
}