<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AuditTrailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_id',
        'investigator_id',
        'action_type',
        'action_performed',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function incident()
    {
        return $this->belongsTo(Incidents::class, 'incident_id');
    }

    public function investigator()
    {
        return $this->belongsTo(Investigator::class, 'investigator_id');
    }

    public static function record(array $attributes): ?self
    {
        if (!Schema::hasTable('audit_trail_logs')) {
            return null;
        }

        return static::create([
            'incident_id' => $attributes['incident_id'] ?? null,
            'investigator_id' => $attributes['investigator_id'] ?? null,
            'action_type' => $attributes['action_type'] ?? 'system',
            'action_performed' => $attributes['action_performed'] ?? 'System activity recorded.',
            'metadata' => $attributes['metadata'] ?? null,
        ]);
    }
}
