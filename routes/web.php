<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PhotoReactionController;

use Illuminate\Support\Facades\Route;

use App\Livewire\Invite\RequestForm;
use App\Livewire\Invite\Consume;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\Photos\Manager as PhotosManager;
use App\Livewire\Admin\InvitesQueue;
use App\Livewire\Admin\PhotosModeration;
use App\Livewire\Invite\SetPassword;
use App\Livewire\Home\Landing;

// Replace any redirect('/') with:
Route::get('/', Landing::class)->name('home');

//Route::redirect('/', '/request-invite');

// Invitation flow (public)
Route::get('/request-invite', RequestForm::class)->name('invite.create');
Route::get('/invite/approve/{token}', Consume::class)->name('invite.consume');

// Auth scaffolding (Breeze)
require __DIR__.'/auth.php';

// Pending approval page
Route::view('/pending-approval', 'auth.pending-approval')->name('pending-approval');

// Authenticated + approved
Route::middleware(['auth','approved'])->group(function () {
    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');
    Route::get('/photos', PhotosManager::class)->name('photos.index');
    Route::post('/photos/{photo}/react.json', [PhotoReactionController::class, 'storeJson'])->name('photos.react.json')->middleware('auth');
});

// Admin moderation (auth only; gate inside components)
Route::middleware(['auth','can:admin'])->group(function () {
    Route::get('/admin/invites', InvitesQueue::class)->name('admin.invites.index');
    Route::get('/admin/photos', PhotosModeration::class)->name('admin.photos.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/invite/set-password/{token}', SetPassword::class)->name('invite.set-password');
