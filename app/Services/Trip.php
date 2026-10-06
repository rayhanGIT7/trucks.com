<?php

namespace App\Services;

use App\Models\Location;

/**
 * What the customer searched for: from where, to where, on which date, and how far it is.
 */
class Trip
{
    public Location $pickup;
    public Location $dropoff;
    public string $date;
    public float $distanceKm;

    public function __construct(Location $pickup, Location $dropoff, string $date, float $distanceKm)
    {
        $this->pickup = $pickup;
        $this->dropoff = $dropoff;
        $this->date = $date;
        $this->distanceKm = $distanceKm;
    }

    /** Values for links, so the trip is carried from page to page: ?pickup=1&dropoff=5&date=... */
    public function toQuery(): array
    {
        return [
            'pickup'  => $this->pickup->id,
            'dropoff' => $this->dropoff->id,
            'date'    => $this->date,
        ];
    }
}
