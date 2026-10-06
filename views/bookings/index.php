<?php
/** @var \App\Models\Booking[] $bookings */
$title = 'My bookings';
?>
<div class="container py-3">
    <h1 class="h4 mb-3">Booking history</h1>

    <?php if (!$bookings): ?>
        <div class="text-center text-muted py-5">
            <i class="bi bi-journal display-4"></i>
            <p class="mt-2">You have no bookings yet.</p>
            <a href="<?= url('/') ?>" class="btn btn-primary">Book a truck</a>
        </div>
    <?php else: ?>
        <div class="table-responsive card shadow-sm">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th>Booking</th><th>Route</th><th>Truck</th><th>Date</th><th class="text-end">Fare</th><th>Status</th><th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td class="fw-semibold"><?= e($booking->booking_no) ?></td>
                        <td class="small"><?= e($booking->pickup_name) ?> → <?= e($booking->dropoff_name) ?></td>
                        <td class="small"><?= e($booking->truck_name) ?></td>
                        <td class="small"><?= format_date($booking->shifting_date) ?></td>
                        <td class="text-end"><?= money($booking->total_fare) ?></td>
                        <td><?= status_badge($booking->status) ?></td>
                        <td><a href="<?= url('/bookings/' . $booking->id) ?>" class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
