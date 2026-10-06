<?php /** @var \App\Models\Booking $booking */ ?>
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Booking <?= e($booking->booking_no) ?></span>
        <?= status_badge($booking->status) ?>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h3 class="h6 text-muted">Trip</h3>
                <p class="mb-1"><i class="bi bi-geo-alt text-success"></i> <strong><?= e($booking->pickup_name) ?></strong><br>
                    <span class="small"><?= e($booking->pickup_address) ?></span></p>
                <p class="mb-1"><i class="bi bi-geo-alt-fill text-danger"></i> <strong><?= e($booking->dropoff_name) ?></strong><br>
                    <span class="small"><?= e($booking->dropoff_address) ?></span></p>
                <p class="mb-3"><i class="bi bi-calendar-event"></i> <?= format_date($booking->shifting_date, 'l, d M Y') ?>
                    · <?= e(ucfirst($booking->shifting_type)) ?> shifting</p>
            </div>
            <div class="col-md-6">
                <h3 class="h6 text-muted">Truck &amp; contact</h3>
                <p class="mb-1"><i class="bi bi-truck"></i> <?= e($booking->truck_name) ?></p>
                <p class="mb-1"><i class="bi bi-person"></i> <?= e($booking->contact_name) ?></p>
                <p class="mb-1"><i class="bi bi-telephone"></i> <?= e($booking->contact_phone) ?></p>
                <?php if ($booking->notes): ?>
                    <p class="small text-muted mb-1"><i class="bi bi-chat-left-text"></i> <?= nl2br(e($booking->notes)) ?></p>
                <?php endif; ?>
            </div>
        </div>
        <table class="table table-sm mb-0 mt-2">
            <tr><td>Distance</td><td class="text-end"><?= number_format($booking->distance_km, 2) ?> km</td></tr>
            <tr><td>Base fare</td><td class="text-end"><?= money($booking->base_fare) ?></td></tr>
            <tr><td>Per km rate</td><td class="text-end"><?= money($booking->per_km_rate) ?></td></tr>
            <tr class="fw-bold fs-5"><td>Total fare</td><td class="text-end text-brand"><?= money($booking->total_fare) ?></td></tr>
        </table>
        <p class="small text-muted mb-0 mt-2">
            Booked on <?= format_date($booking->created_at, 'd M Y, h:i A') ?>
            <?php if ($booking->confirmed_at): ?> · Confirmed on <?= format_date($booking->confirmed_at, 'd M Y, h:i A') ?><?php endif; ?>
        </p>
    </div>
</div>
