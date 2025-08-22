<?php

namespace App\Policies;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PhotoPolicy
{
    use HandlesAuthorization;

    /**
     * A user can delete their own photo, or any photo if they're an admin.
     */
    public function delete(?User $user, Photo $photo): bool
    {
        if (!$user) return false;
        return $user->id === $photo->user_id || $user->role === 'admin';
    }
}