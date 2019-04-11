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

    protected $appends = [
        'profile_photo',
    ];

    public function getProfilePhotoAttribute()
    {
        $photo = $this->files()->where('description', 'profile_photo')->first();
        return $photo ? "$photo->path/$photo->name" . '?date=' . date('Y_m_d_H_i_s') : null;
    }

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
        return $this->files()->where('description', 'profile_photo')->first();
    }

    public function files()
    {
        return $this->morphMany('App\File', 'fileable');
    }
}
