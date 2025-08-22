<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class InvitationRequest extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'full_name','email','grad_year','answers','status','admin_notes','approval_token'
    ];

    protected $casts = [
        'answers' => 'array',
    ];
}