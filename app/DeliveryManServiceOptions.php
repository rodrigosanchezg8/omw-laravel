<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryManServiceOptions extends Model
{

    protected $table = 'delivery_men';

    protected $fillable = [
        'user_id',
        'service_range_id',
        'available'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function service_range()
    {
        return $this->belongsTo('App\ServiceRange', 'service_range_id');
    }

}