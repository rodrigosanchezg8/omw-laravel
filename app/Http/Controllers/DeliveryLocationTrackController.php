<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeliveryLocationTrackStore;
use App\Services\DeliveryLocationTrackService;
use Illuminate\Http\Request;

class DeliveryLocationTrackController extends Controller
{
    public function __construct(DeliveryLocationTrackService $service)
    {
        $this->service = $service;
    }

    public function store(DeliveryLocationTrackStore $request)
    {
        try {

            $deliveryLocationTrack = $this->service->store($request->all());

            return response()->json([
                'status' => 'success',
                'delivery_location_track' => $deliveryLocationTrack,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }
}
