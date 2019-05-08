<?php

namespace App\Services;

use App\Delivery;
use App\DeliveryStatus;
use App\DeliveryLocationTrack;
use App\Location;
use Illuminate\Support\Facades\Auth;

class DeliveryLocationTrackService
{
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
        $delivery = Delivery::whereId($data['delivery_id'])->with('locationTracks')->first();
        $data['step'] = count($delivery->locationTracks) + 1;

        if ($data['step'] - 1 == 0) {
            $inProgressStatus = DeliveryStatus::where('status', config('constants.delivery_statuses.in_progress'))
                ->first();

            $delivery->deliveryStatus()->dissociate();
            $delivery->deliveryStatus()->associate($inProgressStatus);
            $delivery->save();
        }

        $location = Location::create(['lat' => $data['lat'], 'lng' => $data['lng']]);
        $data['location_id'] = $location->id;

        return DeliveryLocationTrack::create($data);
    }
}
