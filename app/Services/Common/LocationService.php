<?php

namespace App\Services\Common;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class LocationService
{
    public static function getCountryFromAddress($address): array
    {
        $locationInfo = [
            'country' => null,
            'state' => null,
            'city' => null,
            'zip_code' => null,
        ];

        $api_key = 'AIzaSyDxMifd5gOyJqcWrVZNxgpgop-Px4T9AuE'; // Replace with your actual API key
        $base_url = 'https://maps.googleapis.com/maps/api/geocode/json';

        $client = new Client();
        $response = $client->get($base_url, [
            'query' => [
                'address' => $address,
                'key' => $api_key,
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        if ($data['status'] == 'OK' && isset($data['results'][0]['address_components'])) {

            foreach ($data['results'] as $result) {
                foreach ($result['address_components'] as $component) {
                    if (in_array('country', $component['types'])) {
                        $locationInfo['country'] = $component['long_name'];
                    } elseif (in_array('administrative_area_level_1', $component['types'])) {
                        $locationInfo['state'] = $component['long_name'];
                    } elseif (in_array('locality', $component['types'])) {
                        $locationInfo['city'] = $component['long_name'];
                    } elseif (in_array('postal_code', $component['types'])) {
                        $locationInfo['zip_code'] = $component['long_name'];
                    } elseif (in_array('establishment', $component['types'])) {
                        $locationInfo['country'] = $component['long_name'];
                    }
                }
            }

            return $locationInfo;
        }

        return $locationInfo;
    }

}
