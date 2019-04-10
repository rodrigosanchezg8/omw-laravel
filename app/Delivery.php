<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'delivery_man_id',
        'sender_id',
        'receiver_id',
        'planned_start_date',
        'planned_end_date',
        'departure_date',
        'arrival_date',
        'departure_location_id',
        'arrival_location_id',
        'delivery_status_id',
    ];

    public function deliveryMan()
    {
        return $this->belongsTo('App\User');
    }

    public function sender()
    {
        return $this->belongsTo('App\User');
    }

    public function receiver()
    {
        return $this->belongsTo('App\User');
    }

    public function departureLocation()
    {
        return $this->belongsTo('App\Location');
    }

    public function arrivalLocation()
    {
        return $this->belongsTo('App\Location');
    }

    public function deliveryStatus()
    {
        return $this->belongsTo('App\DeliveryStatus');
    }

    public function products()
    {
        return $this->hasMany('App\DeliveryProduct');
    }
}
