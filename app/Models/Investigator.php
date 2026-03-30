<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Investigator extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'badge_number',
        'full_name',
        'email',
        'password',
        'profile_image',
        'status',
    ];

    protected $hidden = [
        'password',
    ];
}
