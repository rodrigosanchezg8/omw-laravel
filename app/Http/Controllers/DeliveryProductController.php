<?php

namespace App\Http\Controllers;

use App\DeliveryProduct;
use App\Http\Requests\DeliveryProductStore;
use App\Services\DeliveryProductService;
use Illuminate\Http\Request;

class DeliveryProductController extends Controller
{
    public function __construct(DeliveryProductService $service)
    {
        $this->service = $service;
    }

    public function store(DeliveryProductStore $request)
    {
        try {

            $deliveryProduct = $this->service->create($request->all());

            return response()->json([
                'status' => 'success',
                'delivery' => $deliveryProduct,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function update(DeliveryProduct $deliveryProduct, DeliveryProductUpdate $request)
    {
        try {

            $this->service->update($deliveryProduct, $request->all());

            return response()->json([
                'status' => 'success',
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }
}
