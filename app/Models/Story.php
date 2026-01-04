<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $fillable = [
        'user_id','memory','teacher','song','now','anonymous','status','is_featured'
    ];

    protected $casts = [
        'anonymous' => 'bool',
        'is_featured' => 'bool',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}