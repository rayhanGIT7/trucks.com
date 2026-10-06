<?php

use App\Core\View;

/**
 * @var \App\Models\Booking $booking
 * @var \App\Models\Payment[] $payments
 * @var string $paymentResult  "success" | "failed" | "cancelled" | "" (from SSLCommerz redirect)
 */
$title = 'Booking ' . $booking->booking_no;
?>
<div class="container py-3">
    <a href="<?= url('/bookings') ?>" class="small"><i class="bi bi-arrow-left"></i> All bookings</a>

    <?php // The message is based on the real booking status, not only on the URL. ?>
    <?php if ($paymentResult !== '' && $booking->isConfirmed()): ?>
        <div class="alert alert-success mt-3"><i class="bi bi-check-circle"></i> Payment successful. Your booking is <strong>confirmed</strong>!</div>
    <?php elseif ($paymentResult === 'cancelled' && $booking->isPending()): ?>
        <div class="alert alert-warning mt-3">You cancelled the payment. The booking is not confirmed yet — you can pay again.</div>
    <?php elseif ($paymentResult !== '' && $booking->isPending()): ?>
        <div class="alert alert-danger mt-3">Payment was not completed. The booking is not confirmed yet — please try again.</div>
    <?php endif; ?>

    <div class="row g-4 mt-1">
        <div class="col-lg-8">
            <?= partial('partials/booking-details', ['booking' => $booking]) ?>
            <?= partial('partials/payments-table', ['payments' => $payments]) ?>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if ($booking->isPending()): ?>
                        <h2 class="h6">Waiting for payment</h2>
                        <p class="small text-muted">Your booking will be confirmed after a successful payment.</p>
                        <form method="post" action="<?= url('/bookings/' . $booking->id . '/pay') ?>">
                            <?= csrf_field() ?>
                            <button class="btn btn-success btn-lg w-100"><i class="bi bi-credit-card"></i> Pay <?= money($booking->total_fare) ?></button>
                        </form>
                        <p class="small text-muted text-center mt-2 mb-3">Secured by SSLCommerz</p>
                        <form method="post" action="<?= url('/bookings/' . $booking->id . '/cancel') ?>" onsubmit="return confirm('Cancel this booking?')">
                            <?= csrf_field() ?>
                            <button class="btn btn-outline-danger btn-sm w-100">Cancel booking</button>
                        </form>
                    <?php elseif ($booking->isConfirmed()): ?>
                        <h2 class="h6 text-success"><i class="bi bi-check-circle"></i> Booking confirmed</h2>
                        <p class="small text-muted mb-0">The truck will arrive at your pickup address on <?= format_date($booking->shifting_date) ?>. Our team will call you before arrival.</p>
                    <?php else: ?>
                        <h2 class="h6">Booking <?= e($booking->status) ?></h2>
                        <a href="<?= url('/') ?>" class="btn btn-primary btn-sm">Make a new booking</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
