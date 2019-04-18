<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'city_id',
        'status',
        'birth_date',
    ];

    protected $appends = [
        'full_name',
        'profile_photo',
        'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $guard_name = 'api';

    public function scopeRoleFilter($query, $role)
    {
        return $query->whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        });
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getProfilePhotoAttribute()
    {
        $photo = $this->files()->where('description', 'profile_photo')->first();
        return $photo ? "$photo->path/$photo->name" . '?date=' . date('Y_m_d_H_i_s') : null;
    }

    public function getRoleAttribute()
    {
        $role = $this->roles()->first();
        return $role ? $role : null;
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function city()
    {
        return $this->belongsTo('App\City');
    }

    public function fixedLocation()
    {
        return $this->locations()
                    ->where('origin', config('constants.location_types.fixed'))
                    ->first();
    }

    public function locations()
    {
        return $this->hasMany('App\Location');
    }

    public function company()
    {
        return $this->hasOne('App\Company');
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
