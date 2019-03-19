<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'city',
        'state_id',
    ];

    public $timestamps = false;

    public function state()
    {
        return $this->belongsTo('App\State');
    }
}
