<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationStore;
use App\Services\LocationService;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct(LocationService $service)
    {
        $this->service = $service;
    }

    public function store(LocationStore $request)
    {
        try {

            $location = $this->service->store($request->all());

            return response()->json([
                'header' => 'Localizacion guardada',
                'status' => 'success',
                'location' => $location,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }
}
