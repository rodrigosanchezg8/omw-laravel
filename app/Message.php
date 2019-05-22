<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'body',
        'delivery_id',
        'user_id_replier',
        'status',
        'created_at',
        'updated_at'
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class, 'delivery_id');
    }

    public function replier()
    {
        return $this->belongsTo(User::class, 'user_id_replier');
    }

    public function scopeDescendant($query)
    {
        return $query->orderBy('id', 'desc');
    }

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d/m H:i:s');
    }

}
