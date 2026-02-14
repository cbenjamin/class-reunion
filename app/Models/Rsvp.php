<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rsvp extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'guest_count',
        'note',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}