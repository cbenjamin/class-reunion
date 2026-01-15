<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Memorial extends Model
{
    protected $fillable = [
        'classmate_name','graduation_year','relationship',
        'submitter_name','submitter_email','obituary_url',
        'bio','disk','photo_path','status','is_featured',
        'approved_by','approved_at',
    ];
}