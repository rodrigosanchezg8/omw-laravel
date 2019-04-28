<?php

namespace App\Services;

use App\DeliveryLocationTrack;
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
        return DeliveryLocationTrack::create($data);
    }
}
