<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoryController extends Controller
{
    public function storeJson(Request $request)
    {
        if (! Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $data = $request->validate([
            'memory'    => ['required','string','min:10','max:600'],
            'teacher'   => ['nullable','string','max:120'],
            'song'      => ['nullable','string','max:120'],
            'now'       => ['nullable','string','max:400'],
            'anonymous' => ['boolean'],
        ]);

        $story = Story::create([
            'user_id'     => Auth::id(),
            'memory'      => trim($data['memory']),
            'teacher'     => $data['teacher'] ?? null,
            'song'        => $data['song'] ?? null,
            'now'         => $data['now'] ?? null,
            'anonymous'   => (bool)($data['anonymous'] ?? false),
            'status'      => 'pending',
            'is_featured' => false,
        ]);

        return response()->json([
            'ok'    => true,
            'id'    => $story->id,
            'flash' => 'Thanks! Your story was submitted for review.',
        ]);
    }
}