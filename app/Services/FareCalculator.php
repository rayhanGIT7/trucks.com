<?php

namespace App\Services;

use App\Core\Config;
use App\Models\Truck;

/**
 * fare = truck base fare + (distance km × truck per-km rate)
 * then: at least `fare.minimum_fare`, rounded up to `fare.round_to` taka.
 *
 * Per-truck rates live in the trucks table; global rules in config.php → fare.
 */
class FareCalculator
{
    private float $minimumFare;
    private int $roundTo;

    public function __construct()
    {
        $this->minimumFare = (float) Config::get('fare.minimum_fare', 0);
        $this->roundTo = max(1, (int) Config::get('fare.round_to', 1));
    }

    public function calculate(Truck $truck, float $distanceKm): Fare
    {
        $distanceCharge = round($distanceKm * $truck->per_km_rate, 2);
        $total = max($truck->base_fare + $distanceCharge, $this->minimumFare);
        $total = ceil($total / $this->roundTo) * $this->roundTo;

        return new Fare($distanceKm, $truck->base_fare, $truck->per_km_rate, $distanceCharge, $total);
    }
}
