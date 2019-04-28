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
        $allowedRelationships = [
            'sender',
            'receiver',
            'deliveryStatus',
        ];

        $allowedStatuses = [
            DeliveryStatus::where('status', config('constants.delivery_statuses.making'))->first()->id,
            DeliveryStatus::where('status', config('constants.delivery_statuses.not_started'))->first()->id,
            DeliveryStatus::where('status', config('constants.delivery_statuses.in_progress'))->first()->id,
            DeliveryStatus::where('status', config('constants.delivery_statuses.finished'))->first()->id,
        ];

        if (Auth::user()->hasRole('delivery_man')) {
            $list = Delivery::with($allowedRelationships)
                               ->where('delivery_man_id', Auth::user()->deliveryMan->id);

        } else {

            $allowedRelationships[] = 'products';
            $allowedStatuses[] = DeliveryStatus::where(
                                                        'status',
                                                        config('constants.delivery_statuses.in_progress')
                                               )->first()->id;

            $list = Delivery::with($allowedRelationships);

            if (Auth::user()->hasRole('client')) {

                if(isset($request['origin'])) {

                    switch ($request['origin']) {
                        case config('constants.origin_types.sender'):
                            $list->where('sender_id', Auth::user()->id);
                            break;
                        case config('constants.origin_types.receiver'):
                            $list->where('receiver_id', Auth::user()->id);
                            break;
                        default:
                            $list->where('sender_id', Auth::user()->id)
                                 ->orWhere('receiver_id', Auth::user()->id);
                    }

                } else {
                    $list->where('sender_id', Auth::user()->id)
                         ->orWhere('receiver_id', Auth::user()->id);
                }
            }

        }

        $list->whereIn('delivery_status_id', $allowedStatuses);

        if (isset($request['status'])) {

            $requestedStatus = DeliveryStatus::where('status', $request['status'])->first()->id;

            $list->where('delivery_status_id', $requestedStatus);
        }

        return $list->get();
    }

    public function create($data)
    {
        return Delivery::create($data);
    }

    public function update(Delivery $delivery, $data)
    {
        if ($this->deliveryCanBeAltered($delivery)) {

            $delivery->update($data);

        } else {
            throw new \Exception("El status de la entrega no permite que se actualicen datos", 1);

        }
    }

    public function destroy(Delivery $delivery)
    {
        if (Auth::user()->hasRole('client') && !$this->deliveryCanBeAltered($delivery)) {
            throw new \Exception("El status de la entrega no permite que se elimine", 1);
        }

        $delivery->locationTracks()->delete();
        $delivery->products();
    }

    public function getDetailedDelivery($delivery_id)
    {
        $allowedRelationships = [
            'deliveryMan',
            'sender',
            'receiver',
            'deliveryStatus',
        ];

        if (!Auth::user()->hasRole('delivery_man')){
            $allowedRelationships[] = 'products';
        }

        return Delivery::with($allowedRelationships)->find($delivery_id);
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

    public function changeStatus(Delivery $delivery, $data)
    {
        if (DeliveryStatus::find($data['delivery_status_id'])->status == config('constants.delivery_statuses.cancelled')) {

            throw new \Exception("No es posible cancelar el envio", 1);

        } else {

            $delivery->delivery_status_id = $data['delivery_status_id'];
            $delivery->save();

        }
    }

    private function deliveryCanBeAltered($delivery)
    {
        return (
            $delivery->deliveryStatus->status == config('constants.delivery_statuses.making')
            ||
            $delivery->deliveryStatus->status == config('constants.delivery_statuses.not_assigned')
        );
    }
}
