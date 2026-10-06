<?php

use App\Models\Booking;

/**
 * @var Booking[] $bookings
 * @var string $status
 * @var string $keyword
 */
$title = 'Bookings';
?>
<h1 class="h4 mb-3">Bookings</h1>

<form method="get" class="row g-2 mb-3">
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">All statuses</option>
            <?php foreach (Booking::STATUSES as $option): ?>
                <option value="<?= $option ?>" <?= $option === $status ? 'selected' : '' ?>><?= ucfirst($option) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-5">
        <input type="search" name="q" value="<?= e($keyword) ?>" class="form-control" placeholder="Booking no, customer name or phone">
    </div>
    <div class="col-md-2 d-grid"><button class="btn btn-primary">Filter</button></div>
</form>

<div class="card shadow-sm">
    <?= partial('admin/bookings/table', ['bookings' => $bookings]) ?>
</div>
