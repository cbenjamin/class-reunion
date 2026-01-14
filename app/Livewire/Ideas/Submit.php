<?php

namespace App\Livewire\Ideas;

use App\Models\Idea;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Submit extends Component
{
    public string  $title = '';
    public ?string $category = null;
    public ?string $details = null;
    public ?int    $budget_estimate = null;
    public bool    $can_volunteer = false;
    public bool    $anonymous = false;

    // spam honeypot
    public ?string $website = null;

    public function rules(): array
    {
        return [
            'title'           => ['required','string','max:140'],
            'category'        => ['nullable','string','max:60'],
            'details'         => ['nullable','string','max:5000'],
            'budget_estimate' => ['nullable','integer','min:0','max:100000'],
            'can_volunteer'   => ['boolean'],
            'anonymous'       => ['boolean'],
        ];
    }

    public function submit(): void
    {
        // Honeypot
        if (filled($this->website)) {
            // pretend success
            session()->flash('status','Thanks! Your idea was received.');
            $this->reset(['title','category','details','budget_estimate','can_volunteer','anonymous']);
            return;
        }

        // simple 60s throttle per user/ip
        $key = 'idea:last:'.(Auth::id() ?: request()->ip());
        if (Cache::has($key)) {
            $this->addError('title','You recently submitted an idea. Please wait a bit and try again.');
            return;
        }
        Cache::put($key, 1, now()->addSeconds(60));

        $this->validate();

        $user = Auth::user();

        Idea::create([
            'user_id'         => $user?->id,
            'user_name'       => $user?->name,
            'user_email'      => $user?->email,
            'title'           => $this->title,
            'category'        => $this->category,
            'details'         => $this->details,
            'budget_estimate' => $this->budget_estimate,
            'can_volunteer'   => $this->can_volunteer,
            'anonymous'       => $this->anonymous,
            'status'          => 'pending',
        ]);

        session()->flash('status','Thanks! Your idea was received and is pending review.');
        $this->reset(['title','category','details','budget_estimate','can_volunteer','anonymous']);
    }

    public function render()
    {
        return view('livewire.ideas.submit');
    }
}