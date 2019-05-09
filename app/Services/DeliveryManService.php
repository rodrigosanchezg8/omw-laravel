<?php

namespace App\Services;

use App\Delivery;
use App\DeliveryMan;
use App\DeliveryStatus;
use App\ServiceRange;
use App\Services\MapQuestService;
use Illuminate\Support\Facades\Log;

class DeliveryManService
{
    public function __construct(MapQuestService $mapquestService)
    {
        $this->mapquestService = $mapquestService;
    }

    public function list()
    {
        return DeliveryMan::with([
            'user',
            'service_range',
        ])->get();
    }

    public function store($data)
    {
        DeliveryMan::whereUserId($data['user_id'])->delete();
        return DeliveryMan::create($data);
    }

    public function getDetailedDeliveryMan($delivery_man_id)
    {
        return DeliveryMan::with([
            'user',
            'service_range',
        ])->whereUserId($delivery_man_id)->first();
    }

    public function closestDeliveryManWithTime(Delivery $delivery)
    {
        $coords = [];
        $coords['origin_lat'] = $delivery->senderLat;
        $coords['origin_lng'] = $delivery->senderLng;
        $coords['destiny_lat'] = $delivery->receiverLat;
        $coords['destiny_lng'] = $delivery->receiverLng;

        $distance = $this->mapquestService->distanceAndTimeBeginingToEnd($coords)['distance'];

        $serviceRanges = $this->getServiceRanges($distance);
        $validStatuses = $this->getValidStatuses();

        if ($serviceRanges) {

            $deliveryMen = DeliveryMan::whereHas('service_range', function ($q) use ($serviceRanges) {
                $q->whereIn('service_ranges.id', $serviceRanges);
            })
                ->where(function ($q) use ($validStatuses) {
                    $q->whereDoesntHave('deliveries')
                        ->orWhereHas('deliveries', function ($q) use ($validStatuses) {
                            $q->whereIn('delivery_status_id', $validStatuses);
                        });
                })
                ->where('available', 1)->get();

            $closestInfo = $this->closestDeliveryManAndTime($deliveryMen, $coords);

            return $closestInfo;

        } else {
            throw new \Exception("No ningún repartidor que trabaje con la distancia proporcionada de: " . $distance, 1);
        }
    }

    private function getServiceRanges($distance)
    {
        if ($distance <= config('constants.distances.local')) {

            return ServiceRange::where('km', '>=', config('constants.distances.local'))
                ->pluck('id')
                ->toArray();

        } else if ($distance > config('constants.distances.local') && $distance <= config('constants.distances.short')) {

            return ServiceRange::where('km', '>=', config('constants.distances.short'))
                ->pluck('id')
                ->toArray();

        } else if ($distance > config('constants.distances.short') && $distance <= config('constants.distances.medium')) {

            return ServiceRange::where('km', '>=', config('constants.distances.medium'))
                ->pluck('id')
                ->toArray();

        } else if ($distance > config('constants.distances.medium') && $distance <= config('constants.distances.medium_large')) {

            return ServiceRange::where('km', '>=', config('constants.distances.medium_large'))
                ->pluck('id')
                ->toArray();

        } else if ($distance > config('constants.distances.medium_large') && $distance <= config('constants.distances.large')) {

            return ServiceRange::where('km', '>=', config('constants.distances.large'))
                ->pluck('id')
                ->toArray();
        } else if ($distance > config('constants.distances.large') && $distance <= config('constants.distances.too_large')) {

            return ServiceRange::where('km', config('constants.distances.too_large'))
                ->pluck('id')
                ->toArray();
        } else {
            return [];
        }
    }

    private function getValidStatuses()
    {
        return DeliveryStatus::whereIn('status', [
            config('constants.delivery_statuses.cancelled'),
            config('constants.delivery_statuses.finished'),
        ])->pluck('id')->toArray();
    }

    private function closestDeliveryManAndTime($deliveryMen, $routeCoords)
    {
        $closestGuy = null;
        $closestDistance = null;
        $totalTime = 0;
        foreach ($deliveryMen as $guy) {
            try {
                $minDistanceFromOrigin = config('constants.min_delivery_man_distance_from_origin');
                $guyFixedLocation = $guy->location;

                $fromGuyToInitialPointInfo = $this->$this->mapquestService->distanceAndTimeBeginingToEnd([
                    'origin_lat' => $guyFixedLocation->lat,
                    'origin_lng' => $guyFixedLocation->lng,
                    'destiny_lat' => $routeCoords['origin_lat'],
                    'destiny_lng' => $routeCoords['origin_lng'],
                ]);

                $distanceFromGuyToInitialPoint = $fromGuyToInitialPointInfo['distance'];
                $timeFromGuyToInitialPoint = $fromGuyToInitialPointInfo['time'];

                $fromGuyToEndPoint = $this->$this->mapquestService->distanceAndTimeBeginingToEnd([
                    'origin_lat' => $guyFixedLocation->lat,
                    'origin_lng' => $guyFixedLocation->lng,
                    'destiny_lat' => $routeCoords['destiny_lat'],
                    'destiny_lng' => $routeCoords['destiny_lng'],
                ]);

                $distanceFromGuyToEndPoint = $fromGuyToEndPoint['distance'];
                $timeFromGuyToEndPoint = $fromGuyToEndPoint['time'];

                if ($distanceFromGuyToEndPoint <= $guy->service_range->km && $distanceFromGuyToInitialPoint <= $minDistanceFromOrigin) {
                    if ($closestGuy == null || ($distanceFromGuyToInitialPoint < $closestDistance)) {
                        $closestDistance = $distanceFromGuyToInitialPoint;
                        $totalTime = $timeFromGuyToInitialPoint + $timeFromGuyToEndPoint;
                        $closestGuy = $guy;
                    }
                }

            } catch (\Exception $e) {
                Log::error("There wasn't a route between the guy and the desired point");
            }

        }

        if (!$closestGuy) {
            throw new \Exception("No hay un repartidor disponible para la ruta seleccionada", 1);
        }

        return [
            'delivery_man' => $closestGuy,
            'total_time' => $totalTime,
        ];

    }
}
