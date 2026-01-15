<?php

namespace App\Livewire\Memorial;

use App\Models\Memorial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Submit extends Component
{
    use WithFileUploads;

    public $classmate_name = '';
    public $graduation_year = '';
    public $relationship = '';
    public $submitter_name = '';
    public $submitter_email = '';
    public $obituary_url = '';
    public $bio = '';
    public $photo; // temp uploaded file

    public function mount(): void
    {
        // Pre-fill submitter when logged in
        if (Auth::check()) {
            $this->submitter_name = Auth::user()->name ?? '';
            $this->submitter_email = Auth::user()->email ?? '';
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'classmate_name'  => ['required','string','max:255'],
            'graduation_year' => ['nullable','string','max:10'],
            'relationship'    => ['nullable','string','max:120'],
            'submitter_name'  => ['nullable','string','max:255'],
            'submitter_email' => ['nullable','email','max:255'],
            'obituary_url'    => ['nullable','url','max:2048'],
            'bio'             => ['nullable','string','max:5000'],
            'photo'           => ['nullable','image','max:4096'], // 4MB
        ]);

        $disk = 'public';
        $path = null;

        if ($this->photo) {
            $path = $this->photo->store('memorials', $disk);
        }

        Memorial::create([
            ...$validated,
            'disk'       => $disk,
            'photo_path' => $path,
            'status'     => 'pending',
        ]);

        $this->reset(['classmate_name','graduation_year','relationship','bio','obituary_url','photo']);
        session()->flash('status', 'Thank you. Your memorial has been submitted and is pending approval.');
    }

    public function render()
    {
        return view('livewire.memorial.submit');
    }
}