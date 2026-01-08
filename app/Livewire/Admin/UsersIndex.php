<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class UsersIndex extends Component
{
    use WithPagination;

    public string $status = 'approved'; // 'approved' | 'denied'
    public string $search = '';

    public function updatingStatus() { $this->resetPage(); }
    public function updatingSearch() { $this->resetPage(); }

    public function approve(int $id): void
    {
        $u = User::findOrFail($id);
        $u->is_approved = true;
        // optional: $u->denied_at = null;
        $u->save();

        session()->flash('status', 'User approved.');
    }

    public function deny(int $id): void
    {
        $u = User::findOrFail($id);
        $u->is_approved = false;
        // optional: $u->denied_at = now();
        $u->save();

        session()->flash('status', 'User denied.');
    }

    public function sendReset(int $id): void
    {
        $u = User::findOrFail($id);
        // Only for approved users (per your spec)
        if (! $u->is_approved) {
            session()->flash('status', 'User is denied. Approve before sending reset.');
            return;
        }

        $status = Password::sendResetLink(['email' => $u->email]);

        session()->flash(
            'status',
            $status === Password::RESET_LINK_SENT
                ? "Password reset email sent to {$u->email}."
                : "Could not send password email (status: {$status})."
        );
    }

    public function render()
    {
        $q = User::query()
            ->when($this->search !== '', function ($q) {
                $s = "%{$this->search}%";
                $q->where(function ($q) use ($s) {
                    $q->where('name','like',$s)->orWhere('email','like',$s);
                });
            })
            ->when($this->status === 'approved', fn($q) => $q->where('is_approved', true))
            ->when($this->status === 'denied', fn($q) => $q->where('is_approved', false))
            ->latest();

        $users = $q->paginate(24);

        return view('livewire.admin.users-index', compact('users'));
    }
}