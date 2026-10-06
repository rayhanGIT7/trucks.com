<?php

namespace App\TruckApi;

use Exception;

/**
 * Fake truck API: reads trucks from dummy-trucks.json.
 * The JSON already uses the format described in TruckProviderInterface.
 */
class DummyTruckProvider implements TruckProviderInterface
{
    private const DATA_FILE = __DIR__ . '/dummy-trucks.json';

    public function fetchTrucks(): array
    {
        $json = json_decode((string) file_get_contents(self::DATA_FILE), true);
        if (!isset($json['data']) || !is_array($json['data'])) {
            throw new Exception('Dummy truck data is invalid.');
        }
        return $json['data'];
    }
}
