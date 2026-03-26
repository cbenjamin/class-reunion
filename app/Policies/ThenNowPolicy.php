<?php

namespace App\Policies;

use App\Models\ThenNow;
use App\Models\User;

class ThenNowPolicy
{
    public function delete(User $user, ThenNow $thenNow): bool
    {
        return $user->id === $thenNow->user_id || $user->role === 'admin';
    }
}
