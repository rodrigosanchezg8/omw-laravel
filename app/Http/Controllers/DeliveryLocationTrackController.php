<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeliveryLocationTrackIndex;
use App\Http\Requests\DeliveryLocationTrackStore;
use App\Services\DeliveryLocationTrackService;
use Illuminate\Http\Request;

class DeliveryLocationTrackController extends Controller
{
    public function __construct(DeliveryLocationTrackService $service)
    {
        $this->service = $service;
    }

    public function index(DeliveryLocationTrackIndex $request)
    {
        try {

            $list = $this->service->index($request->all());

            return response()->json([
                'header' => 'Track Actualizado',
                'status' => 'success',
                'count' => $list->count(),
                'tracks' => $list,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function store(DeliveryLocationTrackStore $request)
    {
        try {

            $deliveryLocationTrack = $this->service->store($request->all());

            return response()->json([
                'header' => 'Entrega actualizada',
                'status' => 'success',
                'delivery_location_track' => $deliveryLocationTrack->load('location'),
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }
}
