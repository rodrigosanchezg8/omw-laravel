<?php

namespace App\Services;

use App\Location;

class LocationService
{
    public function store($data)
    {
        return Location::create($data);
    }
}
