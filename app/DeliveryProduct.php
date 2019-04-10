<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryProduct extends Model
{
    protected $fillable = [
        'delivery_id',
        'name',
        'description',
        'amount',
        'cost',
    ];

    public function delivery()
    {
        return $this->belongsTo('App\Delivery');
    }
}
