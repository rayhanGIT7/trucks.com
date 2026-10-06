<?php

namespace App\Services;

use App\Core\Config;
use App\Models\Location;

/**
 * Distance between two locations in km.
 *
 * Uses the Haversine formula (straight line on the earth's surface) and multiplies
 * by a road factor, because roads are never straight.
 * To use Google Maps / another API later, change only this class.
 */
class DistanceCalculator
{
    private const EARTH_RADIUS_KM = 6371;

    private float $roadFactor;

    public function __construct()
    {
        $this->roadFactor = (float) Config::get('distance.road_factor', 1.3);
    }

    public function between(Location $from, Location $to): float
    {
        $lat1 = deg2rad($from->latitude);
        $lat2 = deg2rad($to->latitude);
        $deltaLat = $lat2 - $lat1;
        $deltaLng = deg2rad($to->longitude - $from->longitude);

        $a = sin($deltaLat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($deltaLng / 2) ** 2;
        $straightKm = self::EARTH_RADIUS_KM * 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($straightKm * $this->roadFactor, 2);
    }
}
