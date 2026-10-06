<?php

namespace App\Models;

use App\Core\Model;

class Location extends Model
{
    protected static string $table = 'locations';

    public string $name = '';
    public string $city = '';
    public float $latitude = 0;
    public float $longitude = 0;
    public bool $is_active = true;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    /** Locations customers can choose from. */
    public static function active(): array
    {
        return self::query('SELECT * FROM locations WHERE is_active = 1 ORDER BY city, name');
    }

    public static function all(): array
    {
        return self::query('SELECT * FROM locations ORDER BY city, name');
    }

    public static function findActive(int $id): ?self
    {
        return self::queryOne('SELECT * FROM locations WHERE id = ? AND is_active = 1', [$id]);
    }

    public function label(): string
    {
        return "{$this->name}, {$this->city}";
    }
}
