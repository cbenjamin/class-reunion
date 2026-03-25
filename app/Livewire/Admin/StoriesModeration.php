<?php

namespace App\Livewire\Admin;

use App\Models\Story;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class StoriesModeration extends Component
{
    public $pending = [];
    public $approved = [];

    public function mount()
    {
        abort_unless(Gate::allows('admin'), 403);
        $this->refreshLists();
    }

    public function refreshLists(): void
    {
        $this->pending  = Story::with('user')
            ->where('status','pending')
            ->latest()
            ->get();

        $this->approved = Story::with('user')
            ->where('status','approved')
            ->latest()
            ->get();
    }

    public function approve(int $id): void
    {
        Story::findOrFail($id)->update(['status' => 'approved']);
        $this->refreshLists();
    }

    public function reject(int $id): void
    {
        Story::findOrFail($id)->update(['status' => 'rejected', 'is_featured' => false]);
        $this->refreshLists();
    }

    public function feature(int $id): void
    {
        Story::findOrFail($id)->update(['is_featured' => true]);
        $this->refreshLists();
    }

    public function unfeature(int $id): void
    {
        Story::findOrFail($id)->update(['is_featured' => false]);
        $this->refreshLists();
    }

    public function render()
    {
        return view('livewire.admin.stories-moderation');
    }
}
