<?php

namespace App\Livewire\Admin;

use App\Models\InvitationRequest;
use App\Models\User;
use App\Notifications\InvitationApproved;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class InvitesQueue extends Component
{
    use WithPagination;

    public function approve(int $id): void
    {
<<<<<<< Updated upstream
=======
        abort_unless(Gate::allows('admin'), 403);
        $this->refreshLists();
    }

    public function refreshLists(): void
    {
        $this->pending = InvitationRequest::where('status','pending')->latest()->get();
        $this->approved = InvitationRequest::where('status','approved')->latest()->get();
        $this->denied = InvitationRequest::where('status','denied')->latest()->get();
    }

    public function approve(int $id)
    {
        abort_unless(Gate::allows('admin'), 403);
>>>>>>> Stashed changes
        $req = InvitationRequest::findOrFail($id);

        // Create/fetch user
        $user = User::firstOrCreate(
            ['email' => $req->email],
            [
                'name'     => $req->name ?? $req->full_name ?? Str::before($req->email, '@'),
                'password' => Hash::make(Str::random(40)),
            ]
        );

        // Mark user approved
        if (! $user->is_approved) {
            $user->is_approved = true;
            $user->save();
        }

        // Update invite + notify
        $req->update([
<<<<<<< Updated upstream
            'status'         => 'approved',
            'approval_token' => Str::random(64),
            'approved_by'    => auth()->id(),
            'approved_at'    => now(),
=======
            'status' => 'approved',
            'approval_token' => Str::random(64),
>>>>>>> Stashed changes
        ]);

        try {
            $req->notify(new InvitationApproved($req));
        } catch (\Throwable $e) {
            \Log::warning('InvitationApproved notify failed', ['error' => $e->getMessage()]);
        }

        // Send password setup link
        $status = Password::sendResetLink(['email' => $user->email]);

        session()->flash(
            'status',
            $status === Password::RESET_LINK_SENT
                ? 'Approved. Password setup email sent.'
                : 'Approved. Password email issue — check logs.'
        );

        $this->resetPage();
    }

    public function reject(int $id): void
    {
<<<<<<< Updated upstream
        InvitationRequest::findOrFail($id)->update([
            'status'      => 'rejected',
            'approved_by' => auth()->id(),
        ]);

        session()->flash('status', 'Invitation rejected.');
        $this->resetPage();
=======
        abort_unless(Gate::allows('admin'), 403);
        InvitationRequest::findOrFail($id)->update(['status' => 'denied']);
        $this->refreshLists();
        session()->flash('status','Request denied.');
>>>>>>> Stashed changes
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
