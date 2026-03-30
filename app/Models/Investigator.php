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

    public function notifications()
    {
        return $this->hasMany(InvestigatorNotification::class, 'investigator_id');
    }

    public function createdNotifications()
    {
        return $this->hasMany(InvestigatorNotification::class, 'created_by_investigator_id');
    }
}
