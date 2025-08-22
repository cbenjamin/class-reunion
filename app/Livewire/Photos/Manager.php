<?php
namespace App\Livewire\Photos;

use App\Models\Photo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Manager extends Component
{
    use WithFileUploads, AuthorizesRequests;

    #[Validate('required|image|max:5120')] public $photo;
    #[Validate('nullable|string|max:140')] public ?string $caption = null;

    public $myPhotos = [];
    public int $pendingCount = 0;

    public function mount() { $this->refreshLists(); }

    public function refreshLists(): void
    {
        $this->myPhotos = auth()->user()->photos()->latest()->get();
        $this->pendingCount = Photo::where('status','pending')->count();
    }

    public function save()
    {
        $this->validate();
        $path = $this->photo->store('reunion/photos', 'public');

        Photo::create([
            'user_id' => auth()->id(),
            'disk' => 'public',
            'path' => $path,
            'caption' => $this->caption,
            'status' => 'pending',
        ]);

        $this->reset(['photo','caption']);
        $this->refreshLists();
        session()->flash('status','Thanks! Your photo is pending approval.');
    }

    public function delete(int $id)
    {
        $photo = Photo::findOrFail($id);

        // AuthZ: owner or admin
        $this->authorize('delete', $photo);

        // Try to delete the file (ignore if it doesn't exist)
        try {
            if (Storage::disk($photo->disk)->exists($photo->path)) {
                Storage::disk($photo->disk)->delete($photo->path);
            }
        } catch (\Throwable $e) {
            // log if you want: \Log::warning('Photo file delete failed', [...]);
        }

        $photo->delete();

        $this->refreshLists();
        session()->flash('status', 'Photo removed.');
    }

    public function render()
    {
        return view('livewire.photos.manager');
    }
}