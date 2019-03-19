<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'name',
        'path',
        'cloud_path',
        'status',
    ];

    public $timestamps = false;
}
