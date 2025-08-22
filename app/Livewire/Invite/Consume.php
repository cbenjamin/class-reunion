<?php
namespace App\Livewire\Invite;

use App\Models\InvitationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
#[Layout('layouts.guest')]
class Consume extends Component
{
    public string $token;

    public function mount(string $token)
    {
        return redirect()->route('invite.set-password', $token);
    }
    public function render() { return view('livewire.invite.consume'); }
}