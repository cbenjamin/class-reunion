<?php

namespace App\Http\Controllers;

use App\Actions\ToggleReaction;
use App\Models\Photo;
use App\Models\PhotoReaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PhotoReactionController extends Controller
{
    public function storeJson(Request $request, Photo $photo)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['like','love','laugh','wow','sad','party'])],
        ]);

        $userId = Auth::id();
        if (! $userId) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        (new ToggleReaction)->handle($photo->id, $userId, $validated['type']);

        $counts = PhotoReaction::where('photo_id', $photo->id)
            ->select('type', DB::raw('count(*) as c'))
            ->groupBy('type')
            ->pluck('c', 'type')
            ->all();

        $mine = PhotoReaction::where('photo_id', $photo->id)
            ->where('user_id', $userId)
            ->value('type');

        return response()->json([
            'ok'     => true,
            'mine'   => $mine,
            'counts' => $counts,
        ]);
    }

    public function store(Request $request, Photo $photo)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['like','love','laugh','wow','sad','party'])],
        ]);

        $userId = Auth::id();
        if (! $userId) {
            return redirect()->route('login');
        }

        (new ToggleReaction)->handle($photo->id, $userId, $validated['type']);

        return redirect()->to(url()->previous().'#photo-'.$photo->id);
    }
}
