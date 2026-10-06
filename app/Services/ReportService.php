<?php

namespace App\Services;

use App\Core\Database;

/**
 * Numbers for the admin dashboard and reports page.
 * Plain SQL aggregates — returns simple arrays for the views.
 */
class ReportService
{
    /** Totals for bookings created between two dates (inclusive). */
    public function summary(string $from, string $to): array
    {
        $row = $this->fetchOne(
            "SELECT COUNT(*) AS total_bookings,
                    SUM(status = 'pending')   AS pending,
                    SUM(status = 'confirmed') AS confirmed,
                    SUM(status = 'completed') AS completed,
                    SUM(status = 'cancelled') AS cancelled,
                    COALESCE(SUM(CASE WHEN status IN ('confirmed', 'completed') THEN total_fare END), 0) AS revenue,
                    COALESCE(SUM(CASE WHEN status IN ('confirmed', 'completed') THEN distance_km END), 0) AS total_km
             FROM bookings
             WHERE DATE(created_at) BETWEEN ? AND ?",
            [$from, $to]
        );

        // MySQL returns numbers as text; turn them into real numbers.
        $summary = [];
        foreach ($row as $key => $value) {
            $summary[$key] = (float) $value;
        }
        return $summary;
    }

    /** Successful payment amount per day. */
    public function revenueByDay(string $from, string $to): array
    {
        return $this->fetchAll(
            "SELECT DATE(paid_at) AS day, COUNT(*) AS payments, SUM(amount) AS amount
             FROM payments
             WHERE status = 'success' AND DATE(paid_at) BETWEEN ? AND ?
             GROUP BY DATE(paid_at)
             ORDER BY day",
            [$from, $to]
        );
    }

    public function topTrucks(string $from, string $to, int $limit = 5): array
    {
        return $this->fetchAll(
            "SELECT t.name, COUNT(*) AS bookings, SUM(b.total_fare) AS revenue
             FROM bookings b
             JOIN trucks t ON t.id = b.truck_id
             WHERE b.status IN ('confirmed', 'completed') AND DATE(b.created_at) BETWEEN ? AND ?
             GROUP BY t.id, t.name
             ORDER BY bookings DESC
             LIMIT " . (int) $limit,
            [$from, $to]
        );
    }

    public function topRoutes(string $from, string $to, int $limit = 5): array
    {
        return $this->fetchAll(
            "SELECT pl.name AS pickup, dl.name AS dropoff, COUNT(*) AS bookings
             FROM bookings b
             JOIN locations pl ON pl.id = b.pickup_location_id
             JOIN locations dl ON dl.id = b.dropoff_location_id
             WHERE b.status IN ('confirmed', 'completed') AND DATE(b.created_at) BETWEEN ? AND ?
             GROUP BY pl.id, dl.id, pl.name, dl.name
             ORDER BY bookings DESC
             LIMIT " . (int) $limit,
            [$from, $to]
        );
    }

    private function fetchAll(string $sql, array $params): array
    {
        $statement = Database::connection()->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    private function fetchOne(string $sql, array $params): array
    {
        return $this->fetchAll($sql, $params)[0] ?? [];
    }
}
