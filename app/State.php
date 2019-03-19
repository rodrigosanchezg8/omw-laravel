<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = [
        'state',
        'country_id',
    ];

    public $timestamps = false;

    public function country()
    {
        return $this->belongsTo('App\Country');
    }
}
