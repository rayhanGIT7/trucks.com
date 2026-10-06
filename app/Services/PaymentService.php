<?php

namespace App\Services;

use App\Core\Config;
use App\Core\Database;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Truck;
use App\Models\User;
use Throwable;

/**
 * Payment flow:
 *   start()  → payment row (initiated) → SSLCommerz page URL
 *   verify() → called by success URL and IPN → validation API → payment success + booking confirmed
 *   fail()   → called by fail/cancel URL → payment failed/cancelled, booking stays pending
 */
class PaymentService
{
    private SslCommerzGateway $gateway;

    public function __construct()
    {
        $this->gateway = new SslCommerzGateway();
    }

    /** Returns the SSLCommerz URL to redirect the customer to. */
    public function start(Booking $booking, User $customer): string
    {
        if (!$booking->isPending()) {
            throw new ServiceException('This booking cannot be paid (status: ' . $booking->status . ').');
        }

        $truck = Truck::findOrFail($booking->truck_id);
        if (!$truck->isFreeOn($booking->shifting_date, $booking->id)) {
            throw new ServiceException('Sorry, this truck is no longer available on that date. Please cancel and book another truck.');
        }

        $booking->touch(); // keep the truck reserved while the customer pays
        $payment = Payment::startFor($booking, Config::get('sslcommerz.currency', 'BDT'));

        try {
            return $this->gateway->createSession($payment, $booking, $customer);
        } catch (Throwable $e) {
            $payment->markUnsuccessful(Payment::STATUS_FAILED, ['error' => $e->getMessage()]);
            error_log($e->getMessage());
            throw new ServiceException('Could not start the payment. Please try again in a moment.');
        }
    }

    /**
     * Verify a payment with SSLCommerz and confirm the booking.
     * Safe to call many times for the same payment (success URL + IPN).
     *
     * $post = data SSLCommerz sent us (tran_id, val_id, ...).
     * Returns the payment, or null if tran_id is unknown.
     */
    public function verify(array $post): ?Payment
    {
        $payment = Payment::findByTranId((string) ($post['tran_id'] ?? ''));
        if ($payment === null || $payment->isSuccess()) {
            return $payment;
        }

        $valId = (string) ($post['val_id'] ?? '');
        if ($valId === '') {
            return $payment;
        }

        $data = $this->gateway->validate($valId);

        if (!$this->isValidPayment($payment, $data)) {
            $payment->markUnsuccessful(Payment::STATUS_FAILED, $data);
            return $payment;
        }

        // Save payment + booking together: both are saved, or nothing is saved.
        $pdo = Database::connection();
        $pdo->beginTransaction();
        try {
            // markSuccess() returns false if another request (IPN or success URL) already did it.
            if ($payment->markSuccess($data)) {
                $booking = Booking::findOrFail($payment->booking_id);
                if ($booking->isPending()) {
                    $booking->confirm();
                }
            }
            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }

        return $payment;
    }

    /** Fail / cancel callback. $status = Payment::STATUS_FAILED or STATUS_CANCELLED */
    public function fail(array $post, string $status): ?Payment
    {
        $payment = Payment::findByTranId((string) ($post['tran_id'] ?? ''));
        if ($payment !== null && $payment->isInitiated()) {
            $payment->markUnsuccessful($status, $post);
        }
        return $payment;
    }

    /** Check the validation API response really matches OUR payment. */
    private function isValidPayment(Payment $payment, array $data): bool
    {
        return in_array($data['status'] ?? '', ['VALID', 'VALIDATED'], true)
            && ($data['tran_id'] ?? '') === $payment->tran_id
            && ($data['currency_type'] ?? '') === $payment->currency
            && abs((float) ($data['currency_amount'] ?? 0) - $payment->amount) < 0.01;
    }
}
