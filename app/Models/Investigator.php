<?php

namespace App\Models;

use App\Notifications\InvestigatorResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Investigator extends Authenticatable
{
    use HasFactory, Notifiable;

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new InvestigatorResetPasswordNotification($token));
    }

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
