<?php

namespace App\Services;

use App\Core\Config;
use App\Models\Truck;
use App\TruckApi\DummyTruckProvider;
use App\TruckApi\HttpTruckProvider;
use App\TruckApi\TruckProviderInterface;

/**
 * Copies trucks from the Truck API into our `trucks` table.
 * New trucks are inserted, existing ones (same external_id) are updated.
 */
class TruckSyncService
{
    private TruckProviderInterface $provider;

    public function __construct()
    {
        // config.php → truck_api.driver decides where trucks come from.
        if (Config::get('truck_api.driver') === 'http') {
            $this->provider = new HttpTruckProvider(Config::get('truck_api.base_url'), Config::get('truck_api.api_key', ''));
        } else {
            $this->provider = new DummyTruckProvider();
        }
    }

    /** Returns how many trucks were created / updated / skipped. */
    public function sync(): array
    {
        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($this->provider->fetchTrucks() as $item) {
            if (empty($item['external_id']) || empty($item['name'])) {
                $skipped++;
                continue;
            }

            $data = [
                'name'         => $item['name'],
                'type'         => $item['type'],
                'size_ft'      => $item['size_ft'],
                'capacity_ton' => $item['capacity_ton'],
                'description'  => $item['description'],
                'image_url'    => $item['image_url'],
                'base_fare'    => $item['base_fare'],
                'per_km_rate'  => $item['per_km_rate'],
                'is_available' => $item['is_available'] ? 1 : 0,
                'synced_at'    => date('Y-m-d H:i:s'),
            ];

            $truck = Truck::findByExternalId($item['external_id']);
            if ($truck !== null) {
                $truck->update($data);
                $updated++;
            } else {
                $data['external_id'] = $item['external_id'];
                Truck::create($data);
                $created++;
            }
        }

        return ['created' => $created, 'updated' => $updated, 'skipped' => $skipped];
    }
}
