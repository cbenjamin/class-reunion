<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Idea extends Model
{
    protected $fillable = [
        'user_id','user_name','user_email',
        'title','category','details','budget_estimate',
        'can_volunteer','anonymous','status',
    ];

    protected $casts = [
        'can_volunteer' => 'boolean',
        'anonymous'     => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}