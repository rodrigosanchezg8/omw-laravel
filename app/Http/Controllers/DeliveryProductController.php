<?php

namespace App\Http\Controllers;

use App\DeliveryProduct;
use App\Http\Requests\DeliveryProductStore;
use App\Http\Requests\DeliveryProductUpdate;
use App\Services\DeliveryProductService;
use Illuminate\Http\Request;

class DeliveryProductController extends Controller
{
    public function __construct(DeliveryProductService $service)
    {
        $this->service = $service;
    }

    public function index()
    {

    }

    public function byDelivery($deliveryId)
    {
        try {

            $list = $this->service->getDeliveryProductByDelivery($deliveryId);

            return response()->json($list);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function show($deliveryProductId)
    {
        try {

            $list = $this->service->getDetailedDeliveryProduct($deliveryProductId);

            return response()->json($list);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }


    public function store(DeliveryProductStore $request)
    {
        try {

            $deliveryProduct = $this->service->create($request->all());

            return response()->json([
                'header' => 'Producto agregado',
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

            $deliveryProduct = $this->service->update($deliveryProduct, $request->all());

            return response()->json([
                'header' => 'Producto actualizado',
                'status' => 'success',
                'delivery_product' => $deliveryProduct,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function destroy(DeliveryProduct $deliveryProduct)
    {
        try {

            $this->service->destroy($deliveryProduct);

            return response()->json([
                'header' => 'Producto Eliminado',
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
