<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class Booking extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [self::STATUS_PENDING, self::STATUS_CONFIRMED, self::STATUS_COMPLETED, self::STATUS_CANCELLED];
    public const SHIFTING_TYPES = ['personal', 'business'];

    protected static string $table = 'bookings';

    public string $booking_no = '';
    public int $user_id = 0;
    public int $truck_id = 0;
    public int $pickup_location_id = 0;
    public int $dropoff_location_id = 0;
    public string $pickup_address = '';
    public string $dropoff_address = '';
    public string $shifting_type = 'personal';
    public string $shifting_date = '';
    public string $contact_name = '';
    public string $contact_phone = '';
    public ?string $notes = null;
    public float $distance_km = 0;
    public float $base_fare = 0;
    public float $per_km_rate = 0;
    public float $total_fare = 0;
    public string $status = self::STATUS_PENDING;
    public ?string $confirmed_at = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    // Extra columns filled by the JOIN in detailsSql() (not stored in bookings table).
    public ?string $truck_name = null;
    public ?string $truck_type = null;
    public ?string $truck_image = null;
    public ?string $pickup_name = null;
    public ?string $dropoff_name = null;
    public ?string $customer_name = null;
    public ?string $customer_email = null;

    /** Booking + truck + locations + customer in one query. */
    private static function detailsSql(): string
    {
        return "SELECT b.*,
                       t.name AS truck_name, t.type AS truck_type, t.image_url AS truck_image,
                       CONCAT(pl.name, ', ', pl.city) AS pickup_name,
                       CONCAT(dl.name, ', ', dl.city) AS dropoff_name,
                       u.name AS customer_name, u.email AS customer_email
                FROM bookings b
                JOIN trucks t     ON t.id = b.truck_id
                JOIN locations pl ON pl.id = b.pickup_location_id
                JOIN locations dl ON dl.id = b.dropoff_location_id
                JOIN users u      ON u.id = b.user_id";
    }

    public static function findWithDetails(int $id): ?self
    {
        return self::queryOne(self::detailsSql() . ' WHERE b.id = ?', [$id]);
    }

    public static function forUser(int $userId): array
    {
        return self::query(self::detailsSql() . ' WHERE b.user_id = ? ORDER BY b.id DESC', [$userId]);
    }

    /** Admin list with optional status filter and search (booking no, customer, phone). */
    public static function search(string $status = '', string $keyword = ''): array
    {
        $sql = self::detailsSql() . ' WHERE 1 = 1';
        $params = [];

        if ($status !== '') {
            $sql .= ' AND b.status = ?';
            $params[] = $status;
        }
        if ($keyword !== '') {
            $sql .= ' AND (b.booking_no LIKE ? OR u.name LIKE ? OR b.contact_phone LIKE ?)';
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }

        return self::query($sql . ' ORDER BY b.id DESC LIMIT 200', $params);
    }

    public static function latest(int $limit = 5): array
    {
        return self::query(self::detailsSql() . ' ORDER BY b.id DESC LIMIT ' . (int) $limit);
    }

    /**
     * Ids of trucks that are busy on a date.
     * Busy = has a confirmed booking that day, or a pending booking touched
     * in the last 30 minutes (that customer is probably paying right now).
     */
    public static function busyTruckIds(string $date, int $exceptBookingId = 0): array
    {
        $statement = Database::connection()->prepare(
            "SELECT DISTINCT truck_id FROM bookings
             WHERE shifting_date = ?
               AND id <> ?
               AND (status = 'confirmed'
                    OR (status = 'pending' AND updated_at > NOW() - INTERVAL 30 MINUTE))"
        );
        $statement->execute([$date, $exceptBookingId]);

        $ids = [];
        foreach ($statement->fetchAll() as $row) {
            $ids[] = (int) $row['truck_id'];
        }
        return $ids;
    }

    /** e.g. TB-20261006-4F2A9C */
    public static function generateBookingNo(): string
    {
        return 'TB-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
    }

    public function belongsTo(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function confirm(): void
    {
        $this->update(['status' => self::STATUS_CONFIRMED, 'confirmed_at' => date('Y-m-d H:i:s')]);
    }

    public function cancel(): void
    {
        $this->update(['status' => self::STATUS_CANCELLED]);
    }

    public function complete(): void
    {
        $this->update(['status' => self::STATUS_COMPLETED]);
    }

    /** Refresh updated_at so the truck stays reserved while the customer pays. */
    public function touch(): void
    {
        Database::connection()->prepare('UPDATE bookings SET updated_at = NOW() WHERE id = ?')->execute([$this->id]);
    }

    /** Statuses the admin is allowed to move this booking to. */
    public function nextStatusesForAdmin(): array
    {
        if ($this->status === self::STATUS_PENDING) {
            return [self::STATUS_CANCELLED];
        }
        if ($this->status === self::STATUS_CONFIRMED) {
            return [self::STATUS_COMPLETED, self::STATUS_CANCELLED];
        }
        return []; // completed / cancelled bookings cannot change
    }
}
