<?php /** @var \App\Models\Booking[] $bookings */ ?>
<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light">
        <tr><th>Booking</th><th>Customer</th><th>Route</th><th>Truck</th><th>Shifting date</th><th class="text-end">Fare</th><th>Status</th><th></th></tr>
        </thead>
        <tbody>
        <?php foreach ($bookings as $booking): ?>
            <tr>
                <td class="fw-semibold small"><?= e($booking->booking_no) ?></td>
                <td class="small"><?= e($booking->customer_name) ?><br><span class="text-muted"><?= e($booking->contact_phone) ?></span></td>
                <td class="small"><?= e($booking->pickup_name) ?> → <?= e($booking->dropoff_name) ?></td>
                <td class="small"><?= e($booking->truck_name) ?></td>
                <td class="small"><?= format_date($booking->shifting_date) ?></td>
                <td class="text-end"><?= money($booking->total_fare) ?></td>
                <td><?= status_badge($booking->status) ?></td>
                <td><a href="<?= url('/admin/bookings/' . $booking->id) ?>" class="btn btn-sm btn-outline-primary">Open</a></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$bookings): ?>
            <tr><td colspan="8" class="text-center text-muted py-4">No bookings found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
