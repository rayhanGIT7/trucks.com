<?php

use App\Core\View;

/**
 * @var \App\Models\Booking $booking
 * @var \App\Models\Payment[] $payments
 */
$title = 'Booking ' . $booking->booking_no;
$nextStatuses = $booking->nextStatusesForAdmin();
?>
<a href="<?= url('/admin/bookings') ?>" class="small"><i class="bi bi-arrow-left"></i> All bookings</a>

<div class="row g-4 mt-1">
    <div class="col-lg-8">
        <?= partial('partials/booking-details', ['booking' => $booking]) ?>
        <?= partial('partials/payments-table', ['payments' => $payments]) ?>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Customer</div>
            <div class="card-body small">
                <p class="mb-1"><?= e($booking->customer_name) ?></p>
                <p class="mb-0 text-muted"><?= e($booking->customer_email) ?></p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">Update status</div>
            <div class="card-body">
                <?php if (!$nextStatuses): ?>
                    <p class="small text-muted mb-0">This booking is <?= e($booking->status) ?>. No further changes.</p>
                <?php else: ?>
                    <?php if ($booking->isPending()): ?>
                        <p class="small text-muted">Pending bookings are confirmed automatically after payment.</p>
                    <?php endif; ?>
                    <?php foreach ($nextStatuses as $next): ?>
                        <form method="post" action="<?= url('/admin/bookings/' . $booking->id . '/status') ?>" class="mb-2"
                              onsubmit="return confirm('Mark this booking as <?= e($next) ?>?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="status" value="<?= e($next) ?>">
                            <button class="btn w-100 <?= $next === 'cancelled' ? 'btn-outline-danger' : 'btn-primary' ?>">Mark as <?= e($next) ?></button>
                        </form>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
