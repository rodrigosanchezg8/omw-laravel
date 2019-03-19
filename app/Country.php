<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'code',
        'country',
    ];

    public $timestamps = false;

    public function states()
    {
        return $this->hasMany('App\State');
    }

    public function cities()
    {
        return $this->hasManyThrough('App\City', 'App\State');
    }
}
