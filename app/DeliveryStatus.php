<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryStatus extends Model
{
    protected $fillable = [
        'status',
    ];

    public $timestamps = false;

    public function scopeNotStarted($query)
    {
        return $query->where('status', config('constants.delivery_statuses.not_started'));
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', config('constants.delivery_statuses.in_progress'));
    }

    public function scopeFinished($query)
    {
        return $query->where('status', config('constants.delivery_statuses.finished'));
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', config('constants.delivery_statuses.cancelled'));
    }

    public static function inProgressStatuses()
    {
        return DeliveryStatus::whereNotIn('status', [
            config('constants.delivery_statuses.finished'),
            config('constants.delivery_statuses.cancelled'),
        ])->pluck('id')
          ->toArray();
    }
}
