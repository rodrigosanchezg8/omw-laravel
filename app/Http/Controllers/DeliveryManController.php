<?php

namespace App\Http\Controllers;

use App\DeliveryMan;
use App\Http\Requests\DeliveryManUpdate;
use App\ServiceRange;
use App\Services\DeliveryManService;
use App\Http\Requests\SignUpDeliveryMan;
use App\Http\Requests\DeliveryManGet;
use Illuminate\Http\Request;

class DeliveryManController extends Controller
{
    public function __construct(DeliveryManService $service)
    {
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

    public function get_available_delivery_man(Delivery $delivery)
    {
        try {

            $closeDeliveryMan = $this->service->getAvailableDeliveryMan($delivery);

            return response()->json([
                'header' => 'Repartidor Encontrado',
                'status' => 'success',
                'delivery_man' => $closeDeliveryMan,
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
