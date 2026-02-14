<?php

namespace App\Livewire\Admin;

use App\Models\Rsvp;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class RsvpsIndex extends Component
{
    use WithPagination;

    public string $status = 'all';     // all|yes|maybe|no
    public string $search = '';
    public string $sortField = 'updated_at';
    public string $sortDir = 'desc';

    // so filters persist on refresh/back
    protected $queryString = [
        'status'    => ['except' => 'all'],
        'search'    => ['except' => ''],
        'sortField' => ['except' => 'updated_at'],
        'sortDir'   => ['except' => 'desc'],
        'page'      => ['except' => 1],
    ];

    public function updatedStatus(): void { $this->resetPage(); }
    public function updatedSearch(): void { $this->resetPage(); }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
            return;
        }

        $this->sortField = $field;
        $this->sortDir = 'asc';
    }

    public function render()
    {
        $q = Rsvp::query()
            ->with('user:id,name,email') // we’ll add relationship in model step below
            ->when($this->status !== 'all', fn($qq) => $qq->where('status', $this->status))
            ->when($this->search !== '', function ($qq) {
                $term = '%'.$this->search.'%';
                $qq->whereHas('user', fn($u) => $u->where('name', 'like', $term)->orWhere('email', 'like', $term))
                   ->orWhere('note', 'like', $term);
            })
            ->orderBy($this->sortField, $this->sortDir);

        $rsvps = $q->paginate(25);

        // simple totals for the header
        $totals = [
            'yes'   => Rsvp::where('status','yes')->count(),
            'maybe' => Rsvp::where('status','maybe')->count(),
            'no'    => Rsvp::where('status','no')->count(),
            'all'   => Rsvp::count(),
        ];

        return view('livewire.admin.rsvps-index', [
            'rsvps' => $rsvps,
            'totals' => $totals,
        ]);
    }
}