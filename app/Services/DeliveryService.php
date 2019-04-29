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
            DeliveryStatus::where('status', config('constants.delivery_statuses.not_assigned'))->first()->id,
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
        $delivery = Delivery::create($data);

        $delivery->load([
            'sender',
            'receiver',
            'sender.location',
            'receiver.location',
            'sender.company.location',
            'receiver.company.location',
            'deliveryStatus',
        ]);

        return $delivery;
    }

    public function update(Delivery $delivery, $data)
    {
        if ($delivery->canBeAltered() && !Auth::user()->isReceivingDelivery($delivery)) {

            $delivery->update($data);

        } else {

            throw new \Exception("El status de la entrega no permite que se actualicen datos", 1);

        }
    }

    public function destroy(Delivery $delivery)
    {
        if (Auth::user()->isReceivingDelivery($delivery)) {

            throw new \Exception("No puedes borrar entregas que no te corresponden", 1);

        }

        if (Auth::user()->hasRole('client') && !$delivery->canBeAltered()) {

            throw new \Exception("El status de la entrega no permite que se elimine", 1);

        }

        $delivery->locationTracks()->delete();
        $delivery->products()->delete();
        $delivery->delete();
    }

    public function getDetailedDelivery($delivery_id)
    {
        $allowedRelationships = [
            'deliveryMan',
            'sender',
            'receiver',
            'deliveryStatus',
            'sender.company',
            'sender.company.location',
            'sender.location',
            'receiver.location',
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

    public function changeStatus(Delivery $delivery, $data)
    {
        $deliveryStatus = DeliveryStatus::where('status', $data['delivery_status'])->first();

        if (!$deliveryStatus) {
            throw new \Exception("Status no encontrado", 1);
        }

        if ($deliveryStatus->status == config('constants.delivery_statuses.cancelled')) {

            throw new \Exception("No es posible cancelar el envio", 1);

        } else {

            $delivery->delivery_status_id = $deliveryStatus->id;
            $delivery->save();

        }
    }

    public function checkIfDeliveryHasNotStarted(Delivery $delivery)
    {
        if (!$delivery->canChangeToNotStarted()) {

            throw new \Exception("El status de la entrega no permite que se actualice", 1);

        }
    }

    public function setNotStartedDelivery(Delivery $delivery, $notStartedDeliveryInfo)
    {
        $delivery->planned_start_date = now()->toDateTimeString();
        $delivery->planned_end_date = now()->addSeconds($notStartedDeliveryInfo['total_time'])
                                           ->addDays(config('constants.default_extra_arrival_time'))
                                           ->toDateTimeString();

        $delivery->delivery_man_id = $notStartedDeliveryInfo['delivery_man']->id;
        $delivery->delivery_status_id = DeliveryStatus::where(
                                                                'status',
                                                                config('constants.delivery_statuses.not_started')
                                                             )->first()
                                                              ->id;

        $delivery->save();

        $delivery->load([
            'sender',
            'receiver',
            'deliveryMan',
            'deliveryMan.user',
            'deliveryMan.service_range',
            'deliveryStatus',
        ]);

        return $delivery;
    }
}
