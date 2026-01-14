<?php

namespace App\Livewire\Admin;

use App\Models\Idea;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class IdeasModeration extends Component
{
    public $pending = [];
    public $approved = [];
    public $rejected = [];

    public function mount() { $this->refreshLists(); }

    public function refreshLists(): void
    {
        $this->pending  = Idea::where('status','pending')->latest()->get();
        $this->approved = Idea::where('status','approved')->latest()->get();
        $this->rejected = Idea::where('status','rejected')->latest()->get();
    }

    public function approve(int $id): void
    {
        Idea::findOrFail($id)->update(['status' => 'approved']);
        $this->refreshLists();
    }

    public function reject(int $id): void
    {
        Idea::findOrFail($id)->update(['status' => 'rejected']);
        $this->refreshLists();
    }

    public function delete(int $id): void
    {
        Idea::findOrFail($id)->delete();
        $this->refreshLists();
        session()->flash('status','Idea deleted.');
    }

    public function render()
    {
        return view('livewire.admin.ideas-moderation');
    }
}