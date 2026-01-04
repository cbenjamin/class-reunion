<?php

namespace App\Livewire\Stories;

use App\Models\Story;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Wizard extends Component
{
    public int $step = 1;

    public string $memory = '';
    public ?string $teacher = null;
    public ?string $song = null;
    public ?string $now = null;
    public bool $anonymous = false;

    protected function rules(): array
    {
        return [
            'memory'   => ['required','string','min:10','max:600'],
            'teacher'  => ['nullable','string','max:120'],
            'song'     => ['nullable','string','max:120'],
            'now'      => ['nullable','string','max:400'],
            'anonymous'=> ['boolean'],
        ];
    }

    public function next(): void
    {
        $this->validateStep();
        $this->step = min($this->step + 1, 3);
    }

    public function back(): void
    {
        $this->step = max($this->step - 1, 1);
    }

    protected function validateStep(): void
    {
        if ($this->step === 1) $this->validateOnly('memory');
        if ($this->step === 2) $this->validateOnly('teacher');
        if ($this->step === 3) $this->validate();
    }

    public function submit(): \Illuminate\Http\RedirectResponse
    {
        $this->validate();

        Story::create([
            'user_id'   => Auth::id(),
            'memory'    => trim($this->memory),
            'teacher'   => $this->teacher ? trim($this->teacher) : null,
            'song'      => $this->song ? trim($this->song) : null,
            'now'       => $this->now ? trim($this->now) : null,
            'anonymous' => $this->anonymous,
            'status'    => 'pending',
            'is_featured'=> false,
        ]);

        session()->flash('status', 'Thanks! Your story was submitted for review.');
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.stories.wizard');
    }
}