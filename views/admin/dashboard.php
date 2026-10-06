<?php
/**
 * @var array $thisMonth  ReportService::summary()
 * @var int $customers
 * @var int $trucks
 * @var \App\Models\Booking[] $latestBookings
 */
$title = 'Dashboard';
$cards = [
    ['Revenue (this month)', money($thisMonth['revenue']), 'bi-cash-stack', 'success'],
    ['Bookings (this month)', (int) $thisMonth['total_bookings'], 'bi-journal-text', 'primary'],
    ['Waiting for payment', (int) $thisMonth['pending'], 'bi-hourglass-split', 'warning'],
    ['Customers', $customers, 'bi-people', 'info'],
    ['Trucks', $trucks, 'bi-truck', 'secondary'],
];
?>
<h1 class="h4 mb-4">Dashboard</h1>

<div class="row g-3 mb-4">
    <?php foreach ($cards as [$label, $value, $icon, $color]): ?>
        <div class="col-6 col-lg">
            <div class="card stat-card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="text-<?= $color ?> mb-1"><i class="bi <?= $icon ?> fs-4"></i></div>
                    <div class="value"><?= e($value) ?></div>
                    <div class="small text-muted"><?= e($label) ?></div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between">
        <span class="fw-semibold">Latest bookings</span>
        <a href="<?= url('/admin/bookings') ?>" class="small">View all</a>
    </div>
    <?= partial('admin/bookings/table', ['bookings' => $latestBookings]) ?>
</div>
