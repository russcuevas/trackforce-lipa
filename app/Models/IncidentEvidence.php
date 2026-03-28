<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentEvidence extends Model
{
    use HasFactory;
    protected $fillable = [
        'incident_id',
        'file_path',
        'file_type',
        'uploaded_at',
    ];
}
