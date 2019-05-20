<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'lat',
        'lng',
        'plain_text_address',
        'int_no',
        'ext_no',
    ];
}
