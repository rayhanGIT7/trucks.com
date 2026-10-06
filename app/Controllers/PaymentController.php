<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Throwable;

/**
 * URLs called by SSLCommerz (registered in routes.php as "external":
 * no session, no CSRF). We only trust what the validation API confirms.
 */
class PaymentController extends Controller
{
    private PaymentService $paymentService;

    public function __construct()
    {
        $this->paymentService = new PaymentService();
    }

    /** Customer's browser comes back here after paying. */
    public function success(): void
    {
        try {
            $payment = $this->paymentService->verify($_POST);
        } catch (Throwable $e) {
            error_log('Payment verify failed: ' . $e->getMessage());
            $payment = Payment::findByTranId((string) ($_POST['tran_id'] ?? ''));
        }

        $result = 'failed';
        if ($payment !== null && $payment->isSuccess()) {
            $result = 'success';
        }
        $this->backToBooking($payment, $result);
    }

    public function fail(): void
    {
        $payment = $this->paymentService->fail($_POST, Payment::STATUS_FAILED);
        $this->backToBooking($payment, 'failed');
    }

    public function cancel(): void
    {
        $payment = $this->paymentService->fail($_POST, Payment::STATUS_CANCELLED);
        $this->backToBooking($payment, 'cancelled');
    }

    /** Server-to-server notification from SSLCommerz (IPN). */
    public function ipn(): void
    {
        try {
            $payment = $this->paymentService->verify($_POST);
            echo $payment ? 'OK: ' . $payment->status : 'Unknown transaction';
        } catch (Throwable $e) {
            error_log('IPN failed: ' . $e->getMessage());
            http_response_code(500);
            echo 'Error';
        }
    }

    private function backToBooking(?Payment $payment, string $result): void
    {
        if ($payment === null) {
            $this->redirect('/bookings');
        }
        $this->redirect('/bookings/' . $payment->booking_id, ['payment' => $result]);
    }
}
