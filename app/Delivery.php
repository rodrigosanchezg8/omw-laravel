<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'delivery_man_id',
        'sender_id',
        'receiver_id',
        'company_is_sending',
        'planned_start_date',
        'planned_end_date',
        'departure_date',
        'arrival_date',
        'delivery_status_id',
    ];

    public function canChangeToNotStarted()
    {
        return (
            $this->deliveryStatus->status == config('constants.delivery_statuses.making')
            ||
            $this->deliveryStatus->status == config('constants.delivery_statuses.not_assigned')
        );
    }

    public function locationTracksCount()
    {
        return $this->locationTracks->count();
    }

    public function scopePlainTextCity($query, $city, $onlySender = null, $onlyCompany = null)
    {
        if ($onlyCompany != null) {
            $query->where('company_is_sending', $onlyCompany);
        }

        if ($onlySender == null) {

            $query->where(function ($query) use ($city){
                $query->whereHas('sender.location', function ($query) use ($city) {
                    $query->where('locations.plain_text_address', 'like', '%'. $city. '%');
                })->orWhereHas('receiver.location', function ($query) use ($city) {
                    $query->where('locations.plain_text_address', 'like', '%'. $city. '%');
                });
            });

        } else if ($onlySender == config('constants.origin_types.sender_flag')) {
            $query->whereHas('sender.location', function ($query) use ($city) {
                $query->where('locations.plain_text_address', 'like', '%'. $city. '%');
            });

        } else if ($onlySender == config('constants.origin_types.receiver_flag')) {

            $query->whereHas('receiver.location', function ($query) use ($city) {
                $query->where('locations.plain_text_address', 'like', '%'. $city. '%');
            });

        }
    }

    public function senderLocation()
    {
        return $this->sender->location;
    }

    public function receiverLocation()
    {
        return $this->receiver->location;
    }

    public function senderLat()
    {
        return $this->company_is_sending
            ? $this->sender->company->location->lat
            : $this->sender->location->lat;
    }

    public function senderLng()
    {
        return $this->company_is_sending
            ? $this->sender->company->location->lng
            : $this->sender->location->lng;
    }

    public function receiverLat()
    {
        return $this->receiver->location->lat;
    }

    public function receiverLng()
    {
        return $this->receiver->location->lng;
    }

    public function getDeliveryManFullName()
    {
        return $this->deliveryMan()->first()->user()->first()->fullName;
    }

    public function deliveryMan()
    {
        return $this->belongsTo('App\DeliveryMan');
    }

    public function sender()
    {
        return $this->belongsTo('App\User');
    }

    public function receiver()
    {
        return $this->belongsTo('App\User');
    }

    public function deliveryStatus()
    {
        return $this->belongsTo('App\DeliveryStatus');
    }

    public function locationTracks()
    {
        return $this->hasMany('App\DeliveryLocationTrack');
    }

    public function products()
    {
        return $this->hasMany('App\DeliveryProduct');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'delivery_id', 'id')->with('replier');
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class, 'delivery_id', 'id')
            ->orderBy('updated_at', 'desc');
    }

    public function canBeAltered()
    {
        return ($this->deliveryStatus->status == config('constants.delivery_statuses.making')
            ||
            $this->deliveryStatus->status == config('constants.delivery_statuses.not_assigned')
        );
    }
}
