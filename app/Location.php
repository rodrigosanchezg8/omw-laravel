<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'user_id',
        'origin',
        'lat',
        'lng',
    ];
}
