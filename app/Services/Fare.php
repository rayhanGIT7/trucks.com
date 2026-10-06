<?php

namespace App\Services;

/**
 * The result of a fare calculation. Just holds numbers for the views.
 */
class Fare
{
    public float $distanceKm;
    public float $baseFare;
    public float $perKmRate;
    public float $distanceCharge; // distanceKm × perKmRate
    public float $total;          // what the customer pays

    public function __construct(float $distanceKm, float $baseFare, float $perKmRate, float $distanceCharge, float $total)
    {
        $this->distanceKm = $distanceKm;
        $this->baseFare = $baseFare;
        $this->perKmRate = $perKmRate;
        $this->distanceCharge = $distanceCharge;
        $this->total = $total;
    }
}
