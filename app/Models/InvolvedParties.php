<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvolvedParties extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_id',
        'full_name',
        'age',
        'sex',
        'role',
        'license_number',
        'injury_severity',
        'statement',
    ];
}
