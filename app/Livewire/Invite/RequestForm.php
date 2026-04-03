<?php
namespace App\Livewire\Invite;

use App\Models\InvitationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminInviteAlert;
use App\Mail\InviteRequestReceived;
use App\Models\EventSetting;

#[Layout('components.layouts.auth')]
class RequestForm extends Component
{
    #[Validate('required|string|max:255')] public string $full_name = '';
    #[Validate('required|email')] public string $email = '';
    #[Validate('nullable|string|max:10')] public ?string $grad_year = null;
    #[Validate('nullable|string|max:255')] public ?string $maiden_name = null;
    #[Validate('nullable|string|max:255')] public ?string $homeroom = null;
    #[Validate('nullable|string|max:1000')] public ?string $interest = null;
    #[Validate('required|string|min:8|confirmed')] public string $password = '';
    public string $password_confirmation = '';

    public bool $submitted = false;

    public function submit()
    {
        $this->validate([
            'full_name'    => ['required', 'string', 'max:255'],
            'email'        => [
                'required',
                'email',
                Rule::unique('invitation_requests', 'email'),
                Rule::unique('users', 'email'),
            ],
            'grad_year'    => ['nullable', 'string', 'max:10'],
            'maiden_name'  => ['nullable', 'string', 'max:255'],
            'homeroom'     => ['nullable', 'string', 'max:255'],
            'interest'     => ['nullable', 'string', 'max:1000'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Create user now with is_approved=false; they can't log in until approved
        User::create([
            'name'        => $this->full_name,
            'email'       => $this->email,
            'password'    => Hash::make($this->password),
            'is_approved' => false,
        ]);

        InvitationRequest::create([
            'full_name' => $this->full_name,
            'email'     => $this->email,
            'grad_year' => $this->grad_year,
            'answers'   => [
                'maiden_name' => $this->maiden_name,
                'homeroom'    => $this->homeroom,
                'interest'    => $this->interest,
            ],
            'status' => 'pending',
        ]);

        $this->submitted = true;

        $eventName = config('app.name');

        // Acknowledgment email to the applicant
        try {
            Mail::to($this->email)->send(
                new InviteRequestReceived(name: $this->full_name, eventName: $eventName)
            );
        } catch (\Throwable $e) {
            \Log::warning('Invite ack email failed', ['error' => $e->getMessage()]);
        }

        // Alert email to the configured contact address
        try {
            $settings = EventSetting::query()->first();
            $contactEmail = $settings?->contact_email;

            if ($contactEmail) {
                $inviteRequest = InvitationRequest::where('email', $this->email)->latest()->first();

                Mail::to($contactEmail)->send(
                    new AdminInviteAlert(request: $inviteRequest, eventName: $eventName)
                );
            }
        } catch (\Throwable $e) {
            \Log::warning('Admin invite alert email failed', ['error' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.invite.request-form');
    }
}
