<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentReportInvestigator extends Model
{
    use HasFactory;

    protected $table = 'incident_report_investigators';

    protected $fillable = [
        'incident_id',
        'investigator_name',
        'rank',
    ];

    public function incident()
    {
        return $this->belongsTo(Incidents::class, 'incident_id');
    }
}
