<?php
namespace App\Livewire\Invite;

use App\Models\InvitationRequest;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Attributes\Layout;
#[Layout('layouts.guest')]
class RequestForm extends Component
{
    #[Validate('required|string|max:255')] public string $full_name = '';
    #[Validate('required|email')] public string $email = '';
    #[Validate('nullable|string|max:10')] public ?string $grad_year = null;
    #[Validate('nullable|string|max:255')] public ?string $maiden_name = null;
    #[Validate('nullable|string|max:255')] public ?string $homeroom = null;
    #[Validate('nullable|string|max:1000')] public ?string $interest = null;

    public bool $submitted = false;

    public function submit()
    {
        $this->validate([
            'email' => ['required','email', Rule::unique('invitation_requests','email')],
        ]);

        InvitationRequest::create([
            'full_name' => $this->full_name,
            'email' => $this->email,
            'grad_year' => $this->grad_year,
            'answers' => [
                'maiden_name' => $this->maiden_name,
                'homeroom' => $this->homeroom,
                'interest' => $this->interest,
            ],
            'status' => 'pending',
        ]);

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.invite.request-form');
    }
}