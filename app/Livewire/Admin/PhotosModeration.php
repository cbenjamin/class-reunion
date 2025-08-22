<?php
namespace App\Livewire\Admin;

use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class PhotosModeration extends Component
{
    public $pending = [];
    public $approved = [];

    public function mount() { $this->refreshLists(); }

    public function refreshLists(): void
    {
        $this->pending  = Photo::where('status','pending')->latest()->get();
        $this->approved = Photo::where('status','approved')->latest()->get();
    }

    public function approve(int $id)
    {
        Photo::findOrFail($id)->update(['status' => 'approved']);
        $this->refreshLists();
    }

    public function reject(int $id)
    {
        Photo::findOrFail($id)->update(['status' => 'rejected']);
        $this->refreshLists();
    }

    public function feature(int $id)
    {
        Photo::findOrFail($id)->update(['is_featured' => true]);
        $this->refreshLists();
    }

    public function unfeature(int $id)
    {
        Photo::findOrFail($id)->update(['is_featured' => false]);
        $this->refreshLists();
    }

    public function delete(int $id)
    {
        $photo = Photo::findOrFail($id);

        try {
            if (Storage::disk($photo->disk)->exists($photo->path)) {
                Storage::disk($photo->disk)->delete($photo->path);
            }
        } catch (\Throwable $e) {
            // optional log
        }

        $photo->delete();
        $this->refreshLists();
        session()->flash('status', 'Photo deleted.');
    }

    public function render()
    {
        return view('livewire.admin.photos-moderation');
    }
}