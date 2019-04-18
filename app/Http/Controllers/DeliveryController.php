<?php

namespace App\Http\Controllers;

use App\Delivery;
use App\Http\Requests\DeliveryAssignGuy;
use App\Http\Requests\DeliveryStore;
use App\Services\DeliveryService;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function __construct(DeliveryService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        try {

            $list = $this->service->list($request->all());

            return response()->json([
                'count' => count($list),
                'list' => $list,
            ]);

        } catch(\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function show($delivery_id, Request $request)
    {
        try {

            $delivery = $this->service->getDetailedDelivery($delivery_id);

            return response()->json([
                'status' => 'success',
                'delivery' => $delivery,
            ]);

        } catch(\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function detail(Delivery $delivery, Request $request)
    {
        try {

            $detail = $this->service->detail($delivery);

            return response()->json([
                'detail' => $detail,
            ]);

        } catch(\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function store(DeliveryStore $request)
    {
        try {

            $delivery = $this->service->create($request->all());

            return response()->json([
                'status' => 'success',
                'delivery' => $delivery,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function cancel(Delivery $delivery, Request $request)
    {
        try {

            $this->service->cancel($delivery);

            return response()->json([
                'status' => 'success'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function assign_delivery_man(Delivery $delivery, DeliveryAssignGuy $request)
    {
        try {

            $this->service->assignDeliveryMan($delivery, $request->all());

            return response()->json([
                'status' => 'success'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }
}
