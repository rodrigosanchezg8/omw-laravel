<?php

namespace App\Services;

use App\Delivery;
use App\DeliveryStatus;
use App\Exceptions\InvalidStatusForCancelling;
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

    public function detail(Delivery $delivery)
    {
        return $delivery->products;
    }

    public function cancel(Delivery $delivery)
    {
        if (
            $delivery->delivery_status_id == null ||
            $delivery->delivery_status_id == DeliveryStatus::notStarted()->first()->id
        ) {
            $delivery->delivery_status_id = DeliveryStatus::cancelled()->first()->id;

            $delivery->save();
        } else {

            $message = "El envio solicitado no puede ser cancelado";

            throw new \Exception($message, 1);

        }

    }
}
