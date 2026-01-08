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
            'status'         => 'approved',
            'approval_token' => Str::uuid(),
            'approved_by'    => auth()->id(),
            'approved_at'    => now(),
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
        InvitationRequest::findOrFail($id)->update([
            'status'      => 'rejected',
            'approved_by' => auth()->id(),
        ]);

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