<?php

namespace App\TruckApi;

use Exception;

/**
 * Real truck API over HTTP: GET {base_url}/trucks
 *
 * Expected response: {"data": [ {...truck...}, ... ]}
 * If the real API uses other field names, change only mapTruck().
 */
class HttpTruckProvider implements TruckProviderInterface
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct(string $baseUrl, string $apiKey = '')
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
    }

    public function fetchTrucks(): array
    {
        $headers = ['Accept: application/json'];
        if ($this->apiKey !== '') {
            $headers[] = 'Authorization: Bearer ' . $this->apiKey;
        }

        $curl = curl_init($this->baseUrl . '/trucks');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $body = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($body === false || $status !== 200) {
            throw new Exception("Truck API request failed (HTTP $status) $curlError");
        }

        $json = json_decode($body, true);
        if (!isset($json['data']) || !is_array($json['data'])) {
            throw new Exception('Truck API returned an unexpected response.');
        }

        $trucks = [];
        foreach ($json['data'] as $item) {
            $trucks[] = $this->mapTruck($item);
        }
        return $trucks;
    }

    /** Convert one truck from the API's format to our format. */
    private function mapTruck(array $item): array
    {
        return [
            'external_id'  => (string) ($item['external_id'] ?? ''),
            'name'         => $item['name'] ?? '',
            'type'         => $item['type'] ?? 'pickup',
            'size_ft'      => (float) ($item['size_ft'] ?? 0),
            'capacity_ton' => (float) ($item['capacity_ton'] ?? 0),
            'description'  => $item['description'] ?? '',
            'image_url'    => $item['image_url'] ?? '',
            'base_fare'    => (float) ($item['base_fare'] ?? 0),
            'per_km_rate'  => (float) ($item['per_km_rate'] ?? 0),
            'is_available' => (bool) ($item['is_available'] ?? true),
        ];
    }
}
