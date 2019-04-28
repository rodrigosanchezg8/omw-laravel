<?php

namespace App\Http\Controllers;

use App\Delivery;
use App\DeliveryMan;
use App\Http\Requests\DeliveryManUpdate;
use App\ServiceRange;
use App\Services\DeliveryService;
use App\Services\DeliveryManService;
use App\Http\Requests\SignUpDeliveryMan;
use App\Http\Requests\DeliveryManGet;
use Illuminate\Http\Request;

class DeliveryManController extends Controller
{
    public function __construct(DeliveryService $deliveryService, DeliveryManService $service)
    {
        $this->deliveryService = $deliveryService;
        $this->service = $service;
    }

    public function index(Request $request)
    {
        try {

            $list = $this->service->list();

            return response()->json([
                'count' => count($list),
                'list' => $list,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function store(DeliveryManUpdate $request)
    {
        $deliveryMan = $this->service->store($request->all());

        return response()->json([
            'header' => 'Éxito',
            'status' => 200,
            'delivery_man' => $deliveryMan,
        ]);
    }

    public function show($delivery_man_id, Request $request)
    {
        try {

            $deliveryMan = $this->service->getDetailedDeliveryMan($delivery_man_id);

            if ($deliveryMan == null) {
                throw new \Exception("No se encontró ningun repartidor", 1);
            }

            return response()->json([
                'header' => 'Repartidor Encontrado',
                'status' => 'success',
                'delivery_man' => $deliveryMan,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function closest_delivery_man(Delivery $delivery)
    {
        try {

            $closestInfo = $this->service->closestDeliveryManWithTime($delivery);
            $delivery = $this->deliveryService->setNotStartedDelivery($delivery, $closestInfo);

            return response()->json([
                'header' => 'Repartidor Encontrado',
                'status' => 'success',
                'delivery_man' => $delivery,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function get_service_ranges()
    {
        return response()->json(ServiceRange::all());
    }

}
