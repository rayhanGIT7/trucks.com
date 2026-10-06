<?php

namespace App\Models;

use App\Core\Model;

class Truck extends Model
{
    /** Truck types shown in admin forms. */
    public const TYPES = ['pickup', 'covered_van', 'medium', 'large'];

    protected static string $table = 'trucks';

    public ?string $external_id = null; // id in the Truck API (null = added by admin)
    public string $name = '';
    public string $type = '';
    public float $size_ft = 0;
    public float $capacity_ton = 0;
    public ?string $description = null;
    public ?string $image_url = null;
    public float $base_fare = 0;
    public float $per_km_rate = 0;
    public bool $is_available = true;
    public ?string $synced_at = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    public static function all(): array
    {
        return self::query('SELECT * FROM trucks ORDER BY size_ft, name');
    }

    public static function findByExternalId(string $externalId): ?Truck
    {
        return self::queryOne('SELECT * FROM trucks WHERE external_id = ?', [$externalId]);
    }

    /** Trucks a customer can book on the given date. */
    public static function availableOn(string $date): array
    {
        $busyTruckIds = Booking::busyTruckIds($date);

        $freeTrucks = [];
        foreach (self::all() as $truck) {
            if ($truck->is_available && !in_array($truck->id, $busyTruckIds)) {
                $freeTrucks[] = $truck;
            }
        }
        return $freeTrucks;
    }

    /**
     * Can this truck be booked on this date?
     * $exceptBookingId: ignore this booking (so a booking does not block itself when paying).
     */
    public function isFreeOn(string $date, int $exceptBookingId = 0): bool
    {
        if (!$this->is_available) {
            return false;
        }
        $busyTruckIds = Booking::busyTruckIds($date, $exceptBookingId);
        return !in_array($this->id, $busyTruckIds);
    }

    /** "covered_van" → "Covered Van" */
    public function typeLabel(): string
    {
        return ucwords(str_replace('_', ' ', $this->type));
    }

    public function imageUrl(): string
    {
        if ($this->image_url) {
            return asset($this->image_url);
        }
        return asset('/assets/images/trucks/9.svg'); // default picture
    }
}
