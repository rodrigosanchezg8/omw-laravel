<?php

namespace App\Services;

use App\Delivery;
use App\DeliveryStatus;
use App\DeliveryLocationTrack;
use App\Location;
use App\Services\LocationService;
use Illuminate\Support\Facades\Auth;

class DeliveryLocationTrackService
{
    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    public function index($data)
    {
        $list = DeliveryLocationTrack::with([
            'delivery',
            'location',
        ]);

        if (isset($data['delivery_id'])) {

            $list->where('delivery_id', $data['delivery_id']);

        }

        return $list->orderBy('step')->get();
    }

    public function store($data)
    {
        $delivery = Delivery::find($data['delivery_id'])->load([
            'locationTracks',
        ]);

        if ($delivery->locationTracksCount() == 0) {
            $inProgressStatus = DeliveryStatus::inProgress()->first();

            $delivery->deliveryStatus()->dissociate();
            $delivery->deliveryStatus()->associate($inProgressStatus);
            $delivery->save();
        }

        $data['step'] = $delivery->locationTracksCount() + 1;

        $location = Location::create([
            'lat' => $data['lat'],
            'lng' => $data['lng'],
        ]);

        $data['location_id'] = $location->id;

        if ($this->locationService->distancesAreTooClose($delivery->receiverLocation, $location)) {
            $finishedStatus = DeliveryStatus::finished()->first();

            $delivery->deliveryStatus()->dissociate();
            $delivery->deliveryStatus()->associate($finishedStatus);
            $delivery->save();
        }

        $deliveryLocationTrack = DeliveryLocationTrack::create($data);

        return $deliveryLocationTrack->load([
            'location',
            'delivery',
            'delivery.deliveryStatus',
        ]);
    }
}
