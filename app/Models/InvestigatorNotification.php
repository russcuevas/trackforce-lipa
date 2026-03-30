<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class InvestigatorNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'investigator_id',
        'incident_id',
        'created_by_investigator_id',
        'type',
        'priority',
        'title',
        'message',
        'action_url',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function investigator()
    {
        return $this->belongsTo(Investigator::class, 'investigator_id');
    }

    public function incident()
    {
        return $this->belongsTo(Incidents::class, 'incident_id');
    }

    public function creator()
    {
        return $this->belongsTo(Investigator::class, 'created_by_investigator_id');
    }

    public function markAsRead(): void
    {
        if ($this->is_read) {
            return;
        }

        $this->forceFill([
            'is_read' => true,
            'read_at' => now(),
        ])->save();
    }

    public static function notifyInvestigator(int $investigatorId, array $attributes): ?self
    {
        if (!Schema::hasTable('investigator_notifications')) {
            return null;
        }

        return static::query()->create(array_merge([
            'investigator_id' => $investigatorId,
            'type' => 'system',
            'priority' => 'medium',
            'title' => 'Notification',
            'message' => '',
        ], $attributes));
    }

    public static function notifyMany(array $investigatorIds, array $attributes): void
    {
        if (!Schema::hasTable('investigator_notifications')) {
            return;
        }

        $ids = collect($investigatorIds)
            ->filter(fn($id) => is_numeric($id) && (int) $id > 0)
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return;
        }

        $now = now();

        static::query()->insert($ids->map(function (int $investigatorId) use ($attributes, $now) {
            return array_merge([
                'investigator_id' => $investigatorId,
                'incident_id' => null,
                'created_by_investigator_id' => null,
                'type' => 'system',
                'priority' => 'medium',
                'title' => 'Notification',
                'message' => '',
                'action_url' => null,
                'is_read' => false,
                'read_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ], $attributes, [
                'investigator_id' => $investigatorId,
            ]);
        })->all());
    }

    public static function notifyActiveInvestigators(array $attributes, array $exceptIds = []): void
    {
        $recipientIds = Investigator::query()
            ->where('status', 'active')
            ->when(!empty($exceptIds), function ($query) use ($exceptIds) {
                $query->whereNotIn('id', $exceptIds);
            })
            ->pluck('id')
            ->all();

        static::notifyMany($recipientIds, $attributes);
    }
}
