<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\PhotoReaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PhotoReactionController extends Controller
{
    // If you want to enforce middleware at the controller level, use a constructor:
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'approved']); // optional 'approved'
    // }

    public function store(Request $request, Photo $photo)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['like','love','laugh','wow','sad','party'])],
        ]);

        $userId = Auth::id();
        if (! $userId) {
            return redirect()->route('login');
        }

        $existing = PhotoReaction::where('photo_id', $photo->id)
            ->where('user_id', $userId)
            ->first();

        if ($existing && $existing->type === $validated['type']) {
            // Toggle off
            $existing->delete();
        } else {
            if ($existing) {
                $existing->update(['type' => $validated['type']]);
            } else {
                PhotoReaction::create([
                    'photo_id' => $photo->id,
                    'user_id'  => $userId,
                    'type'     => $validated['type'],
                ]);
            }
        }

        return redirect()->to(url()->previous().'#photo-'.$photo->id);
    }
}