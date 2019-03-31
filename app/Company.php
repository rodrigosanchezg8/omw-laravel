<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'city_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function city()
    {
        return $this->belongsTo('App\City');
    }

    public function profilePhoto()
    {
        return $this->files()->where('description', 'profile_image')->first();
    }

    public function files()
    {
        return $this->morphMany('App\File', 'fileable');
    }
}
