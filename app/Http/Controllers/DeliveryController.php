<?php

namespace App\Http\Controllers;

use App\Delivery;
use App\Http\Requests\DeliveryChangeStatus;
use App\Http\Requests\DeliveryStore;
use App\Http\Requests\DeliveryUpdate;
use App\Services\DeliveryManService;
use App\Services\DeliveryService;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function __construct(DeliveryManService $deliveryManService, DeliveryService $service)
    {
        $this->deliveryManService = $deliveryManService;
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

        } catch (\Exception $e) {

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
                'header' => 'Entrega encontrada',
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

    public function store(DeliveryStore $request)
    {
        try {

            $delivery = $this->service->create($request->all());

            return response()->json([
                'header' => 'Entrega guardada',
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

    public function update(Delivery $delivery, DeliveryUpdate $request)
    {
        try {

            $delivery = $this->service->update($delivery, $request->all());

            return response()->json([
                'header' => 'Entrega Actualizada',
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

    public function destroy(Delivery $delivery)
    {
        try {

            $this->service->destroy($delivery);

            return response()->json([
                'header' => 'Entrega Eliminada',
                'status' => 'success',
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
                'header' => 'Entrega Cancelada',
                'status' => 'success'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function set_not_started_protocol(Delivery $delivery)
    {
        try {

            $this->service->checkIfDeliveryHasNotStarted($delivery);

            $closestInfo = $this->deliveryManService->closestDeliveryManWithTime($delivery);
            $delivery = $this->service->setNotStartedDelivery($delivery, $closestInfo);

            return response()->json([
                'header' => 'Repartidor Encontrado',
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

    public function change_status(Delivery $delivery, DeliveryChangeStatus $request)
    {
        try {

            $this->service->changeStatus($delivery, $request->all());

            return response()->json([
                'header' => 'Su entrega ha sido enviada',
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
