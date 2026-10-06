<?php
/**
 * @var \App\Models\Truck[] $trucks
 * @var \App\Services\Trip|null $trip
 * @var \App\Services\Fare[] $fares   keyed by truck id
 * @var string $error  message when the search was wrong
 */
$title = 'Available trucks';

// The trip in the URL, so links keep the customer's search.
$tripQuery = [];
if ($trip !== null) {
    $tripQuery = $trip->toQuery();
}
?>
<div class="container py-3">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <?= partial('partials/trip-search', ['locations' => $locations, 'trip' => $trip]) ?>
            <?php if ($error !== ''): ?>
                <div class="text-danger small mt-2"><?= e($error) ?></div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($trip): ?>
        <div class="alert alert-info d-flex flex-wrap gap-3 align-items-center">
            <span><i class="bi bi-geo-alt"></i> <strong><?= e($trip->pickup->label()) ?></strong>
                <i class="bi bi-arrow-right"></i> <strong><?= e($trip->dropoff->label()) ?></strong></span>
            <span><i class="bi bi-signpost-2"></i> ~<?= number_format($trip->distanceKm, 1) ?> km</span>
            <span><i class="bi bi-calendar-event"></i> <?= format_date($trip->date) ?></span>
        </div>
        <h1 class="h4 mb-3"><?= count($trucks) ?> trucks available</h1>
    <?php else: ?>
        <h1 class="h4 mb-1">Our trucks</h1>
        <p class="text-muted">Choose pickup, destination and date above to see availability and exact fare.</p>
    <?php endif; ?>

    <div class="row g-4">
        <?php foreach ($trucks as $truck): ?>
            <?php $detailsUrl = url('/trucks/' . $truck->id, $tripQuery); ?>
            <div class="col-sm-6 col-lg-4">
                <div class="card h-100 truck-card">
                    <img src="<?= e($truck->imageUrl()) ?>" class="card-img-top truck-img" alt="<?= e($truck->name) ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h2 class="h6 fw-bold mb-1"><?= e($truck->name) ?></h2>
                            <span class="badge text-bg-light border"><?= e($truck->typeLabel()) ?></span>
                        </div>
                        <p class="small text-muted mb-2">
                            <?= e($truck->size_ft) ?> ft · <?= e($truck->capacity_ton) ?> ton
                            <?php if (!$truck->is_available): ?> · <span class="text-danger">Not available</span><?php endif; ?>
                        </p>
                        <?php if ($trip): ?>
                            <div class="fs-4 fw-bold text-brand"><?= money($fares[$truck->id]->total) ?></div>
                            <div class="small text-muted">Total fare for <?= number_format($trip->distanceKm, 1) ?> km</div>
                        <?php else: ?>
                            <div class="small">From <strong><?= money($truck->base_fare) ?></strong> + <?= money($truck->per_km_rate) ?>/km</div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer bg-white border-0 pb-3 d-flex gap-2">
                        <a href="<?= e($detailsUrl) ?>" class="btn btn-outline-primary btn-sm flex-fill">Details</a>
                        <?php if ($trip): ?>
                            <a href="<?= e(url('/bookings/create', array_merge(['truck' => $truck->id], $tripQuery))) ?>" class="btn btn-primary btn-sm flex-fill">Book now</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (!$trucks): ?>
            <div class="col-12 text-center text-muted py-5">
                <i class="bi bi-truck display-4"></i>
                <p class="mt-2">No trucks available<?= $trip ? ' on this date. Please try another date.' : '.' ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
