<?php

namespace App\Actions;

use App\Models\PhotoReaction;
use Illuminate\Support\Facades\DB;

class ToggleReaction
{
    /**
     * Toggle a reaction for a user on a photo.
     *
     * Returns:
     *   mine        => string|null  — the user's current reaction after toggle (null if removed)
     *   toggled_off => bool         — true if the reaction was removed
<<<<<<< Updated upstream
     *   previous    => string|null  — the reaction type that was replaced (for UI cleanup)
=======
     *   previous    => string|null  — the previous reaction type (for optimistic UI cleanup)
>>>>>>> Stashed changes
     *
     * @return array{mine: string|null, toggled_off: bool, previous: string|null}
     */
    public function handle(int $photoId, int $userId, string $type): array
    {
        $existing = PhotoReaction::where('photo_id', $photoId)
            ->where('user_id', $userId)
            ->first();

<<<<<<< Updated upstream
        // Toggle off if same type clicked again
=======
        // Toggle off if same type clicked
>>>>>>> Stashed changes
        if ($existing && $existing->type === $type) {
            $existing->delete();
            return ['mine' => null, 'toggled_off' => true, 'previous' => $type];
        }

        $previous = $existing?->type;

        DB::transaction(function () use ($photoId, $userId, $type, $existing) {
            if ($existing) {
                $existing->update(['type' => $type]);
            } else {
                PhotoReaction::create([
                    'photo_id' => $photoId,
                    'user_id'  => $userId,
                    'type'     => $type,
                ]);
            }
        });

        return ['mine' => $type, 'toggled_off' => false, 'previous' => $previous];
    }
}
