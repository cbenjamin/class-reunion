<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSetting extends Model
{
    protected $fillable = [
        'event_name','event_date','event_time','venue','address','details','rsvp_enabled',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];
}