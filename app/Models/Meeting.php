<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Meeting extends Model
{
  protected $fillable = [
    'room_id',
    'type',
    'priority',
    'title',
    'organizer',
    'start_time',
    'end_time',
];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function scopeOverlapping(Builder $query, int $roomId, Carbon $start, Carbon $end, ?int $excludeMeetingId = null): Builder
    {
        return $query->where('room_id', $roomId)
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->when($excludeMeetingId, function ($q) use ($excludeMeetingId) {
                $q->where('id', '!=', $excludeMeetingId);
            });
    }
}