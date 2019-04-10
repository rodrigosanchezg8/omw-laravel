<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryLocationTrack extends Model
{
    protected $fillable = [
        'delivery_id',
        'location_id',
        'step',
    ];

    public function delivery()
    {
        return $this->belongsTo('App\Delivery');
    }

    public function location()
    {
        return $this->belongsTo('App\Location');
    }
}
