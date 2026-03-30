<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidents extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_number',
        'incident_type',

        // Location
        'location_name',
        'latitude',
        'longitude',
        'road_condition',
        'weather_condition',

        // Reporter
        'reporter_name',
        'reporter_contact',
        'reporter_email',
        'reporter_address',

        // Status
        'status',
        'time_reported',
        'time_documented',
        'time_completed',
        'otp',
        'is_verified',

        // Assignment
        'assigned_investigator_id',
    ];

    public function notifications()
    {
        return $this->hasMany(InvestigatorNotification::class, 'incident_id');
    }
}
