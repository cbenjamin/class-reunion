<?php

namespace App\Livewire\Admin;

use App\Models\InvitationRequest;
use App\Models\User;
use App\Notifications\InvitationApproved;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class InvitesQueue extends Component
{
    use WithPagination;

    public function approve(int $id): void
    {
        $req = InvitationRequest::findOrFail($id);

        // User was created at request time with is_approved=false
        $user = User::where('email', $req->email)->firstOrFail();

        if (! $user->is_approved) {
            $user->is_approved = true;
            $user->approved_at = now();
            $user->save();
        }

        $req->update([
            'status'      => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        try {
            $user->notify(new InvitationApproved($req));
        } catch (\Throwable $e) {
            \Log::warning('InvitationApproved notify failed', ['error' => $e->getMessage()]);
        }

        session()->flash('status', 'Approved. User can now log in.');
        $this->resetPage();
    }

    public function reject(int $id): void
    {
        $req = InvitationRequest::findOrFail($id);

        $req->update([
            'status'      => 'rejected',
            'approved_by' => auth()->id(),
        ]);

        // User remains with is_approved=false — they cannot log in

        session()->flash('status', 'Invitation rejected.');
        $this->resetPage();
    }

    public function render()
    {
        $pending = InvitationRequest::query()
            ->where('status', 'pending')
            ->latest()
            ->paginate(24);

        return view('livewire.admin.invites-queue', compact('pending'));
    }
}
