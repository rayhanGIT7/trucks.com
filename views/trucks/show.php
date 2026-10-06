<?php
/**
 * @var \App\Models\Truck $truck
 * @var \App\Services\Trip|null $trip
 * @var \App\Services\Fare|null $fare
 * @var bool $isAvailable
 */
$title = $truck->name;

// The trip in the URL, so links keep the customer's search.
$tripQuery = [];
if ($trip !== null) {
    $tripQuery = $trip->toQuery();
}
?>
<div class="container py-3">
    <a href="<?= e(url('/trucks', $tripQuery)) ?>" class="small"><i class="bi bi-arrow-left"></i> Back to trucks</a>

    <div class="row g-4 mt-1">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <img src="<?= e($truck->imageUrl()) ?>" class="card-img-top truck-img" style="height: 280px" alt="<?= e($truck->name) ?>">
                <div class="card-body">
                    <h1 class="h3"><?= e($truck->name) ?></h1>
                    <p class="text-muted"><?= e($truck->description) ?></p>
                    <table class="table table-sm mb-0">
                        <tr><th class="w-50">Type</th><td><?= e($truck->typeLabel()) ?></td></tr>
                        <tr><th>Size</th><td><?= e($truck->size_ft) ?> feet</td></tr>
                        <tr><th>Capacity</th><td><?= e($truck->capacity_ton) ?> ton</td></tr>
                        <tr><th>Base fare</th><td><?= money($truck->base_fare) ?></td></tr>
                        <tr><th>Rate per km</th><td><?= money($truck->per_km_rate) ?></td></tr>
                        <tr><th>Status</th><td><?= $isAvailable ? '<span class="text-success">Available</span>' : '<span class="text-danger">Not available' . ($trip ? ' on ' . format_date($trip->date) : '') . '</span>' ?></td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">Fare calculation</div>
                <div class="card-body">
                    <?php if ($trip && $fare): ?>
                        <p class="small mb-2">
                            <i class="bi bi-geo-alt text-success"></i> <?= e($trip->pickup->label()) ?><br>
                            <i class="bi bi-geo-alt-fill text-danger"></i> <?= e($trip->dropoff->label()) ?><br>
                            <i class="bi bi-calendar-event"></i> <?= format_date($trip->date) ?>
                        </p>
                        <?= partial('partials/fare-table', ['fare' => $fare]) ?>
                        <?php if ($isAvailable): ?>
                            <a href="<?= e(url('/bookings/create', array_merge(['truck' => $truck->id], $tripQuery))) ?>" class="btn btn-primary w-100 mt-3">Book this truck</a>
                        <?php else: ?>
                            <div class="alert alert-warning mt-3 mb-0">This truck is not available on the selected date.</div>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="small text-muted">Choose your route to see the exact fare.</p>
                        <form method="get" action="<?= url('/trucks/' . $truck->id) ?>">
                            <select name="pickup" class="form-select mb-2" required>
                                <option value="">Pickup location</option>
                                <?php foreach ($locations as $location): ?>
                                    <option value="<?= $location->id ?>"><?= e($location->label()) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select name="dropoff" class="form-select mb-2" required>
                                <option value="">Destination</option>
                                <?php foreach ($locations as $location): ?>
                                    <option value="<?= $location->id ?>"><?= e($location->label()) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="date" name="date" class="form-control mb-2" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                            <button class="btn btn-primary w-100">Calculate fare</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
