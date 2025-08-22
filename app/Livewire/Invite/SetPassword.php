<?php

namespace App\Livewire\Invite;

use App\Models\InvitationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class SetPassword extends Component
{
    public string $token;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;

        $req = InvitationRequest::where('approval_token', $token)
            ->where('status', 'approved')
            ->firstOrFail();

        // Pre-fill
        $this->name  = $req->full_name;
        $this->email = $req->email;
    }

    public function save()
    {
        $this->validate([
            'name' => ['required','string','max:255'],
            'password' => ['required','string','min:8','confirmed'],
        ]);

        $req = InvitationRequest::where('approval_token', $this->token)
            ->where('status', 'approved')
            ->firstOrFail();

        $user = User::firstOrNew(['email' => $req->email]);
        $user->name = $this->name;
        $user->password = Hash::make($this->password);
        $user->is_approved = true;
        $user->save();

        // Single-use token
        $req->update(['approval_token' => null]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('status', 'Password set! Welcome in 🎉');
    }

    public function render()
    {
        return view('livewire.invite.set-password');
    }
}