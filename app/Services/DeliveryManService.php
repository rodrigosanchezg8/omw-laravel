<?php

namespace App\Services;

use App\Delivery;
use App\DeliveryMan;
use App\ServiceRange;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DeliveryManService {

    public function __construct(Client $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
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
        return DeliveryMan::create($data);
    }

    public function getDetailedDeliveryMan($delivery_man_id)
    {
        return DeliveryMan::with([
            'user',
            'service_range',
        ])->find($delivery_man_id);
    }

    public function getAvailableDeliveryMan($coords)
    {
        $distance = $this->getDistanceBeginingtoEnd($coords);

        $serviceRange = $this->getServiceRange($distance);

        if ($serviceRange) {

            $deliveryMen = DeliveryMan::whereHas('service_range', function ($q) use ($serviceRange) {
                $q->where('service_ranges.id', $serviceRange->id);
            })->where('available', 1)->get();

            $deliveryMan = $this->getCloserDeliveryMan($deliveryMen, $coords);

            return $deliveryMan;

        } else {
            throw new \Exception("No ningún repartidor que trabaje con la distancia proporcionada de: ". $distance, 1);
        }
    }

    private function getServiceRange($distance)
    {
        if ($distance <= config('constants.distances.short')) {

            return ServiceRange::where('km', config('constants.distances.short'))->first();

        } else if ($distance > config('constants.distances.short') && $distance <= config('constants.distances.medium')) {

            return ServiceRange::where('km', config('constants.distances.medium'))->first();

        } else if ($distance > config('constants.distances.medium') && $distance <= config('constants.distances.long')) {

            return ServiceRange::where('km', config('constants.distances.long'))->first();

        } else {

            return null;

        }
    }

    private function getDistanceBeginingtoEnd($coords)
    {
        $apiKey = config('apis.mapquest.customer_key');

        $url = config('apis.mapquest.mapquest_distance_matrix_url');
        $url = str_replace('mapquestApiKey', $apiKey, $url);

        $payload = [
            "locations" => [
                [
                    "latLng" => [
                        "lat" => $coords['origin_lat'],
                        "lng" => $coords['origin_lng'],
                    ],
                ],
                [
                    "latLng" => [
                        "lat" => $coords['destiny_lat'],
                        "lng" => $coords['destiny_lng'],
                    ]
                ],
            ],
        ];

        $response = $this->guzzleClient->post($url, [
            'json' => $payload,
        ]);

        $jsonResponse = json_decode($response->getBody()->getContents());

        if (!isset($jsonResponse->distance)) {
            throw new \Exception("No hay una ruta entre los dos puntos proporcionados", 1);
        }

        return $jsonResponse->distance[1];
    }

    private function getCloserDeliveryMan($deliveryMen, $routeCoords)
    {
        foreach ($deliveryMen as $guy) {

            try {
                $minDistanceFromOrigin  = config('constants.min_delivery_man_distance_from_origin');
                $guyFixedLocation = $guy->user->fixedLocation();

                $distanceFromGuyToInitialPoint = $this->getDistanceBeginingtoEnd([
                    'origin_lat' => $guyFixedLocation->lat,
                    'origin_lng' => $guyFixedLocation->lng,
                    'destiny_lat' => $routeCoords['origin_lat'],
                    'destiny_lng' => $routeCoords['origin_lng'],
                ]);

                $distanceFromGuyToEndPoint = $this->getDistanceBeginingtoEnd([
                    'origin_lat' => $guyFixedLocation->lat,
                    'origin_lng' => $guyFixedLocation->lng,
                    'destiny_lat' => $routeCoords['destiny_lat'],
                    'destiny_lng' => $routeCoords['destiny_lng'],
                ]);

                if ($distanceFromGuyToEndPoint <= $guy->service_range->km  && $distanceFromGuyToInitialPoint <= $minDistanceFromOrigin) {

                        return $guy;
                }

            } catch (\Exception $e) {
                Log::error("There wasn't a route between the guy and the desired point");
            }

        }

        throw new \Exception("No hay un repartidor disponible para la ruta seleccionada", 1);

    }
}
