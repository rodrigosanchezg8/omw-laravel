<?php

namespace App\Services;

use GuzzleHttp\Client;

class MapQuestService
{
    public function __construct(Client $guzzleClient)
    {
        $this->mapquestApiKey = config('apis.mapquest.customer_key');
        $this->mapquestBaseUrl = config('apis.mapquest.base_url');
        $this->mapquestDistanceMatrixUrl = config('apis.mapquest.distance_matrix_url');
        $this->mapquestReverseGeocoding = config('apis.mapquest.reverse_geocoding');
        $this->guzzleClient = $guzzleClient;
    }

    public function distanceAndTimeBeginingToEnd($coords)
    {
        $url = $this->mapquestBaseUrl. $this->mapquestDistanceMatrixUrl;
        $url = str_replace('mapquestApiKey', $this->mapquestApiKey, $url);

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

        return [
            'distance' => $jsonResponse->distance[1],
            'time' => $jsonResponse->time[1],
        ];
    }

    //TODO: improve the algorithm so that it can be able to build the string
    //using the actual Country, State and City, not only the hardcoded values
    public function getFormattedAddressString($coords)
    {
        $baseUrl = $this->mapquestBaseUrl;
        $url = $baseUrl. $this->mapquestReverseGeocoding;
        $url = str_replace('mapquestApiKey', $this->mapquestApiKey, $url);
        $url = str_replace('locationLat', $coords['lat'], $url);
        $url = str_replace('locationLng', $coords['lng'], $url);

        $response = $this->guzzleClient->request('GET', $url);

        $jsonResponse = json_decode($response->getBody()->getContents());

        $street = $jsonResponse->results[0]->locations[0]->street;
        $city = $jsonResponse->results[0]->locations[0]->adminArea4;
        $state = $jsonResponse->results[0]->locations[0]->adminArea3;
        $country = $jsonResponse->results[0]->locations[0]->adminArea1;

        return $street. ','. $city. ','. $state. ','. $country;
    }
}
