<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'delivery_man_id',
        'sender_id',
        'receiver_id',
        'company_is_sending',
        'planned_start_date',
        'planned_end_date',
        'departure_date',
        'arrival_date',
        'delivery_status_id',
    ];

    public function getSenderLatAttribute()
    {
        return $this->company_is_sending
                ? $this->sender->company->location->lat
                : $this->sender->location->lat;
    }

    public function getSenderLngAttribute()
    {
        return $this->company_is_sending
                ? $this->sender->company->location->lng
                : $this->sender->location->lng;
    }

    public function canChangeToNotStarted()
    {
        return (
                  $this->deliveryStatus->status == config('constants.delivery_statuses.making')
                  ||
                  $this->deliveryStatus->status == config('constants.delivery_statuses.not_assigned')
               );
    }

    public function getReceiverLatAttribute()
    {
        return $this->receiver->location->lat;
    }

    public function getReceiverLngAttribute()
    {
        return $this->receiver->location->lng;
    }

    public function deliveryMan()
    {
        return $this->belongsTo('App\DeliveryMan');
    }

    public function sender()
    {
        return $this->belongsTo('App\User');
    }

    public function receiver()
    {
        return $this->belongsTo('App\User');
    }

    public function deliveryStatus()
    {
        return $this->belongsTo('App\DeliveryStatus');
    }

    public function locationTracks()
    {
        return $this->hasMany('App\DeliveryLocationTrack');
    }

    public function products()
    {
        return $this->hasMany('App\DeliveryProduct');
    }

    public function canBeAltered()
    {
        return ($this->deliveryStatus->status == config('constants.delivery_statuses.making')
                ||
               $this->deliveryStatus->status == config('constants.delivery_statuses.not_assigned')
        );
    }
}
