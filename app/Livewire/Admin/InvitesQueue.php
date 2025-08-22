<?php
namespace App\Livewire\Admin;

use App\Models\InvitationRequest;
use App\Notifications\InvitationApproved;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
#[Layout('layouts.app')]
class InvitesQueue extends Component
{
    public $pending = [];
    public $approved = [];
    public $denied = [];

    public function mount()
    {
        //abort_unless(Gate::allows('admin'), 403);
        $this->refreshLists();
    }

    public function refreshLists(): void
    {
        $this->pending = InvitationRequest::where('status','pending')->latest()->get();
        $this->approved = InvitationRequest::where('status','approved')->latest()->get();
        $this->denied = InvitationRequest::where('status','denied')->latest()->get();
    }

    public function approve(int $id)
    {
        //abort_unless(Gate::allows('admin'), 403);
        $req = InvitationRequest::findOrFail($id);
        $req->update([
            'status' => 'approved',
            'approval_token' => Str::uuid(),
        ]);
        $req->notify(new InvitationApproved($req));
        $this->refreshLists();
        session()->flash('status','Approved & email sent.');
    }

    public function deny(int $id)
    {
        //abort_unless(Gate::allows('admin'), 403);
        InvitationRequest::findOrFail($id)->update(['status' => 'denied']);
        $this->refreshLists();
        session()->flash('status','Request denied.');
    }

    public function render()
    {
        return view('livewire.admin.invites-queue');
    }
}