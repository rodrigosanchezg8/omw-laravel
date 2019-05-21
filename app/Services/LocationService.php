<?php

namespace App\Services;

use App\Location;
use App\Services\MapQuestService;

class LocationService
{
    public function __construct(MapQuestService $mapquestService)
    {
        $this->mapquestService = $mapquestService;
    }

    public function store($data)
    {
        return Location::create($data);
    }

    public function distancesAreTooClose(Location $origin, Location $destiny)
    {
        $coords = [];
        $coords['origin_lat'] = $origin->lat;
        $coords['origin_lng'] = $origin->lng;
        $coords['destiny_lat'] = $destiny->lat;
        $coords['destiny_lng'] = $destiny->lng;

        $distance = $this->mapquestService->distanceAndTimeBeginingToEnd($coords)['distance'];

        return $distance <= config('constants.distances.too_close');
    }

    public function getFormattedAddressString(Location $location)
    {
        $coords = [];
        $coords['lat'] = $location->lat;
        $coords['lng'] = $location->lng;

        return $this->mapquestService->getFormattedAddressString($coords);
    }
}
