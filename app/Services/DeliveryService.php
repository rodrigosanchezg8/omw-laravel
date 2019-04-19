<?php

namespace App\Services;

use App\Delivery;
use App\DeliveryMan;
use App\DeliveryStatus;
use Illuminate\Support\Facades\Auth;

class DeliveryService
{
    public function list($request)
    {
        if (isset($request['statuses'])) {

            $list = Delivery::with([
                'sender',
                'receiver',
                'departureLocation',
                'arrivalLocation',
                'deliveryStatus',
                'products',
            ])->whereIn('delivery_status_id', explode('|', $request['statuses']));

        } else {

            $list = Delivery::with([
                'sender',
                'receiver',
                'departureLocation',
                'arrivalLocation',
                'deliveryStatus',
                'products',
            ]);

        }


        if (Auth::user()->hasRole('client|company|delivery_man')) {
            if (Auth::user()->hasRole('delivery_man')) {
                $list->where('delivery_man_id', Auth::user()->id);
            } else {
                $list->where(function ($inner_query) {
                    $inner_query->where('sender_id', Auth::user()->id)
                                ->orWhere('receiver_id', Auth::user()->id);
                });
            }
        }

        return $list->get();
    }

    public function create($data)
    {
        return Delivery::create($data);
    }

    public function getDetailedDelivery($delivery_id)
    {
        return Delivery::with([
            'deliveryMan',
            'sender',
            'receiver',
            'departureLocation',
            'arrivalLocation',
            'deliveryStatus',
        ])->find($delivery_id);
    }

    public function detail(Delivery $delivery)
    {
        return Auth::user()->hasRole('delivery_man') ? []
                                                     : $delivery->products;
    }

    public function cancel(Delivery $delivery)
    {
        if (
            $delivery->delivery_status_id == null ||
            $delivery->delivery_status_id == DeliveryStatus::notStarted()->first()->id
        ) {

            $delivery->delivery_status_id = DeliveryStatus::cancelled()->first()->id;

            $delivery->save();

            $deliveryMan = DeliveryMan::find($delivery->delivery_man_id);

            $deliveryMan->available = config('constants.delivery_man_statuses.bussy');
            $deliveryMan->save();

        } else {

            throw new \Exception("El envio solicitado no puede ser cancelado", 1);

        }

    }

    public function assignDeliveryMan(Delivery $delivery, $data)
    {
        $delivery->delivery_man_id = $data->delivery_man_id;

        $deliveryMan = DeliveryMan::find($data->delivery_man_id);

        $deliveryMan->available = config('constants.delivery_man_statuses.bussy');
        $deliveryMan->save();
    }
}