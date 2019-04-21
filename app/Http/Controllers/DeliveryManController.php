<?php

namespace App\Http\Controllers;

use App\DeliveryMan;
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

    public function store(SignUpDeliveryMan $request)
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
                throw new \Exception("no se encontró ningun repartidor disponible", 1);
            }

            return response()->json([
                'status' => 'success',
                'delivery_man' => $deliveryMan,
            ]);

        } catch(\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function get_available_delivery_man(DeliveryManGet $request)
    {
        try {

            $closeDeliveryMan = $this->service->getAvailableDeliveryMan($request->all());

            return response()->json([
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
