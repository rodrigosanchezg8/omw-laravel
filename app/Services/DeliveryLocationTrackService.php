<?php

namespace App\Services;

use Carbon\Carbon;
use stdClass;
use App\Location;
use App\Delivery;
use App\Message;
use App\Services\MessageService;
use App\DeliveryStatus;
use App\DeliveryLocationTrack;
use App\Services\LocationService;
use Illuminate\Support\Facades\Auth;

class DeliveryLocationTrackService
{
    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    public function index($data)
    {
        $list = DeliveryLocationTrack::with([
            'delivery',
            'location',
        ]);

        if (isset($data['delivery_id'])) {

            $list->where('delivery_id', $data['delivery_id']);

        }

        return $list->orderBy('step')->get();
    }

    public function store($data)
    {
        $delivery = Delivery::find($data['delivery_id'])->load([
            'locationTracks',
        ]);

        if ($delivery->locationTracksCount() == 0) {
            $inProgressStatus = DeliveryStatus::inProgress()->first();

            $delivery->deliveryStatus()->dissociate();
            $delivery->deliveryStatus()->associate($inProgressStatus);
            $delivery->departure_date = Carbon::now();
            $delivery->save();

            $deliveryManFullName = $delivery->getDeliveryManFullName();
            $messageData = new stdClass();
            $messageData->body = "Tu entrega ha sido comenzada por el repartidor $deliveryManFullName en la fecha:
             $delivery->departure_date";
            $messageData->body = substr($messageData->body, 0, 254);
            $messageData->user_id_receiver = $delivery->receiver_id;
        }

        $data['step'] = $delivery->locationTracksCount() + 1;

        $location = Location::create([
            'lat' => $data['lat'],
            'lng' => $data['lng'],
        ]);

        $data['location_id'] = $location->id;

        if ($this->locationService->distancesAreTooClose($delivery->receiverLocation, $location)) {
            $finishedStatus = DeliveryStatus::finished()->first();

            $delivery->deliveryStatus()->dissociate();
            $delivery->deliveryStatus()->associate($finishedStatus);
            $delivery->arrival_date = Carbon::now();
            $delivery->save();

            $messageData = new stdClass();
            $messageData->body = "Tu entrega ha sido finalizada por el repartidor en la fecha: $delivery->arrival_date";
            $messageData->user_id_receiver = $delivery->receiver_id;
        }

        $deliveryLocationTrack = DeliveryLocationTrack::create($data);

        if (!isset($messageData)) {
            $messageData = new stdClass();
            $messageData->body = "La localización de tu entrega ha sido actualizada por el repartidor, puedes 
            verificarla en el mapa";
            $messageData->user_id_receiver = $delivery->receiver_id;
        }
        MessageService::create($messageData, $delivery);

        return $deliveryLocationTrack->load([
            'location',
            'delivery',
            'delivery.deliveryStatus',
        ]);
    }
}
