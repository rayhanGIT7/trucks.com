<?php

namespace App\TruckApi;

/**
 * Anything that can give us a list of trucks (dummy file, real REST API, ...).
 *
 * To use a different truck API, write a new class that implements this interface
 * and choose it in TruckSyncService::__construct() (config truck_api.driver).
 */
interface TruckProviderInterface
{
    /**
     * Each truck must be an array with these keys:
     *   external_id   string  id of the truck in the API
     *   name          string
     *   type          string  pickup | covered_van | medium | large
     *   size_ft       float
     *   capacity_ton  float
     *   description   string
     *   image_url     string  absolute URL or path inside public/
     *   base_fare     float   BDT
     *   per_km_rate   float   BDT per km
     *   is_available  bool
     *
     * @throws \Exception when the API cannot be reached
     */
    public function fetchTrucks(): array;
}
