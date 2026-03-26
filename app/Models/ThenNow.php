<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThenNow extends Model
{
    protected $fillable = [
        'user_id',
        'then_disk', 'then_path',
        'now_disk',  'now_path',
        'caption',
        'status',
        'approved_by', 'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
