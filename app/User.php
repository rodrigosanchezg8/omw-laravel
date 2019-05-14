<?php

namespace App;

use App\Thread;
use App\Delivery;
use App\DeliveryStatus;
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
        'location_id',
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

    public function isReceivingDelivery(Delivery $delivery)
    {
        return ($this->deliveriesAsReceiverInProgress()
                ->where('id', $delivery->id)
                ->count()) > 0;
    }

    public function isSendingDelivery(Delivery $delivery)
    {
        return ($this->deliveriesAsSenderInProgress()
                ->where('id', $delivery->id)
                ->count()) > 0;
    }

    public function isAdmin()
    {
        return $this->roles()->first()->name == 'admin';
    }

    public function scopeRoleFilter($query, $role)
    {
        return $query->whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        });
    }

    public function scopeFullName()
    {
        return "{$this->first_name} {$this->last_name}";
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

    public function deliveriesAsSender()
    {
        return $this->hasMany('App\Delivery', 'sender_id');
    }

    public function deliveriesAsReceiver()
    {
        return $this->hasMany('App\Delivery', 'receiver_id');
    }

    public function deliveriesAsSenderInProgress()
    {
        $inProgressStatuses = DeliveryStatus::inProgressStatuses();

        return $this->deliveriesAsSender()
            ->whereIn('delivery_status_id', $inProgressStatuses);
    }

    public function deliveriesAsReceiverInProgress()
    {
        $inProgressStatuses = DeliveryStatus::inProgressStatuses();

        return $this->deliveriesAsReceiver()
            ->whereIn('delivery_status_id', $inProgressStatuses);
    }

    public function location()
    {
        return $this->belongsTo('App\Location');
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

    public function deliveryMan()
    {
        return $this->hasOne('App\DeliveryMan');
    }
}
