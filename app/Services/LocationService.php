<?php

namespace App\Services;

use App\Location;
use GuzzleHttp\Client;

class LocationService
{
    public function __construct(Client $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    public function store($data)
    {
        return Location::create($data);
    }

    public function distancesAreTooClose(Location $origin, Location $destiny)
    {
        $apiKey = config('apis.mapquest.customer_key');

        $baseUrl = config('apis.mapquest.base_url');
        $url = $baseUrl. config('apis.mapquest.distance_matrix_url');
        $url = str_replace('mapquestApiKey', $apiKey, $url);

        $payload = [
            "locations" => [
                [
                    "latLng" => [
                        "lat" => $origin->lat,
                        "lng" => $origin->lng,
                    ],
                ],
                [
                    "latLng" => [
                        "lat" => $destiny->lat,
                        "lng" => $destiny->lng,
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
        
        return $jsonResponse->distance[1] <= config('constants.distances.too_close');
    }
}
