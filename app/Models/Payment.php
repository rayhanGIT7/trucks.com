<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class Payment extends Model
{
    public const STATUS_INITIATED = 'initiated';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [self::STATUS_INITIATED, self::STATUS_SUCCESS, self::STATUS_FAILED, self::STATUS_CANCELLED];

    protected static string $table = 'payments';

    public int $booking_id = 0;
    public string $tran_id = '';
    public float $amount = 0;
    public string $currency = 'BDT';
    public string $status = self::STATUS_INITIATED;
    public ?string $val_id = null;
    public ?string $bank_tran_id = null;
    public ?string $card_type = null;
    public ?string $gateway_response = null;
    public ?string $paid_at = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    // Filled by the JOIN in search().
    public ?string $booking_no = null;
    public ?string $customer_name = null;

    public static function startFor(Booking $booking, string $currency): self
    {
        return self::create([
            'booking_id' => $booking->id,
            'tran_id'    => 'TRX' . date('YmdHis') . strtoupper(bin2hex(random_bytes(4))),
            'amount'     => $booking->total_fare,
            'currency'   => $currency,
            'status'     => self::STATUS_INITIATED,
        ]);
    }

    public static function findByTranId(string $tranId): ?self
    {
        return self::queryOne('SELECT * FROM payments WHERE tran_id = ?', [$tranId]);
    }

    public static function forBooking(int $bookingId): array
    {
        return self::query('SELECT * FROM payments WHERE booking_id = ? ORDER BY id DESC', [$bookingId]);
    }

    public static function search(string $status = '', string $keyword = ''): array
    {
        $sql = 'SELECT p.*, b.booking_no, u.name AS customer_name
                FROM payments p
                JOIN bookings b ON b.id = p.booking_id
                JOIN users u    ON u.id = b.user_id
                WHERE 1 = 1';
        $params = [];

        if ($status !== '') {
            $sql .= ' AND p.status = ?';
            $params[] = $status;
        }
        if ($keyword !== '') {
            $sql .= ' AND (p.tran_id LIKE ? OR b.booking_no LIKE ? OR p.bank_tran_id LIKE ?)';
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }

        return self::query($sql . ' ORDER BY p.id DESC LIMIT 200', $params);
    }

    public function isInitiated(): bool
    {
        return $this->status === self::STATUS_INITIATED;
    }

    public function isSuccess(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    /**
     * $gatewayData = response of the SSLCommerz validation API.
     * Returns false if the payment was already marked success (by the IPN or success URL),
     * so the booking is confirmed only once.
     */
    public function markSuccess(array $gatewayData): bool
    {
        $statement = Database::connection()->prepare(
            "UPDATE payments
             SET status = ?, val_id = ?, bank_tran_id = ?, card_type = ?, gateway_response = ?, paid_at = NOW()
             WHERE id = ? AND status <> ?"
        );
        $statement->execute([
            self::STATUS_SUCCESS,
            $gatewayData['val_id'] ?? null,
            $gatewayData['bank_tran_id'] ?? null,
            $gatewayData['card_type'] ?? null,
            json_encode($gatewayData),
            $this->id,
            self::STATUS_SUCCESS,
        ]);

        $this->status = self::STATUS_SUCCESS;
        return $statement->rowCount() === 1;
    }

    /** $status = failed or cancelled. */
    public function markUnsuccessful(string $status, array $gatewayData): void
    {
        $this->update([
            'status'           => $status,
            'gateway_response' => json_encode($gatewayData),
        ]);
    }
}
