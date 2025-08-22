<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','disk','path','caption','status','is_featured'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function user() { return $this->belongsTo(User::class); }
}