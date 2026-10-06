<?php
/**
 * @var string $from
 * @var string $to
 * @var array $summary
 * @var array $revenue    rows: day, payments, amount
 * @var array $topTrucks  rows: name, bookings, revenue
 * @var array $topRoutes  rows: pickup, dropoff, bookings
 */
$title = 'Reports';
$maxAmount = max(array_column($revenue, 'amount') ?: [1]);
$cards = [
    ['Revenue', money($summary['revenue'])],
    ['Bookings', (int) $summary['total_bookings']],
    ['Confirmed', (int) $summary['confirmed']],
    ['Completed', (int) $summary['completed']],
    ['Pending', (int) $summary['pending']],
    ['Cancelled', (int) $summary['cancelled']],
    ['Km booked', number_format($summary['total_km'], 1)],
];
?>
<div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
    <h1 class="h4 mb-0">Reports</h1>
    <form method="get" class="d-flex gap-2 align-items-end">
        <div><label class="form-label small mb-0">From</label><input type="date" name="from" value="<?= e($from) ?>" class="form-control form-control-sm"></div>
        <div><label class="form-label small mb-0">To</label><input type="date" name="to" value="<?= e($to) ?>" class="form-control form-control-sm"></div>
        <button class="btn btn-primary btn-sm">Show</button>
    </form>
</div>

<div class="row g-3 mb-4">
    <?php foreach ($cards as [$label, $value]): ?>
        <div class="col-6 col-md-3 col-xl">
            <div class="card stat-card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="value"><?= e($value) ?></div>
                    <div class="small text-muted"><?= e($label) ?></div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">Daily revenue (successful payments)</div>
            <div class="card-body">
                <?php foreach ($revenue as $row): ?>
                    <div class="d-flex align-items-center gap-2 mb-2 small">
                        <span style="width: 80px"><?= format_date($row['day'], 'd M') ?></span>
                        <div class="flex-grow-1 bg-light rounded">
                            <div class="bg-success rounded" style="height: 14px; width: <?= max(2, round($row['amount'] / $maxAmount * 100)) ?>%"></div>
                        </div>
                        <span style="width: 110px" class="text-end"><?= money($row['amount']) ?></span>
                    </div>
                <?php endforeach; ?>
                <?php if (!$revenue): ?><p class="text-muted small mb-0">No payments in this period.</p><?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Top trucks</div>
            <table class="table table-sm mb-0">
                <thead class="table-light"><tr><th>Truck</th><th class="text-end">Bookings</th><th class="text-end">Revenue</th></tr></thead>
                <tbody>
                <?php foreach ($topTrucks as $row): ?>
                    <tr><td><?= e($row['name']) ?></td><td class="text-end"><?= (int) $row['bookings'] ?></td><td class="text-end"><?= money($row['revenue']) ?></td></tr>
                <?php endforeach; ?>
                <?php if (!$topTrucks): ?><tr><td colspan="3" class="text-muted small">No data.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">Top routes</div>
            <table class="table table-sm mb-0">
                <thead class="table-light"><tr><th>Route</th><th class="text-end">Bookings</th></tr></thead>
                <tbody>
                <?php foreach ($topRoutes as $row): ?>
                    <tr><td><?= e($row['pickup']) ?> → <?= e($row['dropoff']) ?></td><td class="text-end"><?= (int) $row['bookings'] ?></td></tr>
                <?php endforeach; ?>
                <?php if (!$topRoutes): ?><tr><td colspan="2" class="text-muted small">No data.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
