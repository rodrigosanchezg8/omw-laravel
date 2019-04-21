<?php

namespace App\Services;

use App\DeliveryLocationTrack;

class DeliveryLocationTrackService
{
    public function store($data)
    {
        return DeliveryLocationTrack::create($data);
    }
}
