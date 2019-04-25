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

    protected $appends = [
        'product_image'
    ];

    public function delivery()
    {
        return $this->belongsTo('App\Delivery');
    }

    public function files()
    {
        return $this->morphMany('App\File', 'fileable');
    }

    public function productImage()
    {
        return $this->files()->where('description', 'product_image')->first();
    }

    public function getProductImageAttribute()
    {
        $photo = $this->files()->where('description', 'product_image')->first();
        return $photo ? "$photo->path/$photo->name" . '?date=' . date('Y_m_d_H_i_s') : null;
    }


}
