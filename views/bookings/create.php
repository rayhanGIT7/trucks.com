<?php
/**
 * @var \App\Models\User $user
 * @var \App\Models\Truck $truck
 * @var \App\Services\Trip $trip
 * @var \App\Services\Fare $fare
 */
$title = 'Booking information';
?>
<div class="container py-3">
    <h1 class="h4 mb-3">Booking information</h1>
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="post" action="<?= url('/bookings') ?>" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="truck" value="<?= $truck->id ?>">
                        <input type="hidden" name="pickup" value="<?= $trip->pickup->id ?>">
                        <input type="hidden" name="dropoff" value="<?= $trip->dropoff->id ?>">
                        <input type="hidden" name="date" value="<?= e($trip->date) ?>">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact name</label>
                                <input type="text" name="contact_name" class="form-control <?= invalid('contact_name') ?>" value="<?= e(old('contact_name', $user->name)) ?>">
                                <div class="invalid-feedback"><?= e(error('contact_name')) ?></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact mobile</label>
                                <input type="tel" name="contact_phone" class="form-control <?= invalid('contact_phone') ?>" value="<?= e(old('contact_phone', $user->phone)) ?>">
                                <div class="invalid-feedback"><?= e(error('contact_phone')) ?></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pickup address <span class="text-muted small">(<?= e($trip->pickup->label()) ?>)</span></label>
                            <input type="text" name="pickup_address" class="form-control <?= invalid('pickup_address') ?>" value="<?= e(old('pickup_address')) ?>" placeholder="House, road, floor">
                            <div class="invalid-feedback"><?= e(error('pickup_address')) ?></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Destination address <span class="text-muted small">(<?= e($trip->dropoff->label()) ?>)</span></label>
                            <input type="text" name="dropoff_address" class="form-control <?= invalid('dropoff_address') ?>" value="<?= e(old('dropoff_address')) ?>" placeholder="House, road, floor">
                            <div class="invalid-feedback"><?= e(error('dropoff_address')) ?></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Shifting type</label>
                            <?php foreach (\App\Models\Booking::SHIFTING_TYPES as $type): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="shifting_type" id="type_<?= $type ?>" value="<?= $type ?>" <?= old('shifting_type', 'personal') === $type ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="type_<?= $type ?>"><?= ucfirst($type) ?> (<?= $type === 'personal' ? 'home' : 'office' ?>)</label>
                                </div>
                            <?php endforeach; ?>
                            <div class="text-danger small"><?= e(error('shifting_type')) ?></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes <span class="text-muted small">(optional)</span></label>
                            <textarea name="notes" rows="3" class="form-control <?= invalid('notes') ?>" placeholder="Number of items, lift available, helpers needed..."><?= e(old('notes')) ?></textarea>
                            <div class="invalid-feedback"><?= e(error('notes')) ?></div>
                        </div>

                        <button class="btn btn-primary btn-lg w-100">Confirm booking &amp; continue to payment</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm">
                <img src="<?= e($truck->imageUrl()) ?>" class="card-img-top truck-img" alt="">
                <div class="card-body">
                    <h2 class="h6 fw-bold"><?= e($truck->name) ?></h2>
                    <p class="small mb-2">
                        <i class="bi bi-geo-alt text-success"></i> <?= e($trip->pickup->label()) ?><br>
                        <i class="bi bi-geo-alt-fill text-danger"></i> <?= e($trip->dropoff->label()) ?><br>
                        <i class="bi bi-calendar-event"></i> <?= format_date($trip->date) ?>
                    </p>
                    <?= partial('partials/fare-table', ['fare' => $fare]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
