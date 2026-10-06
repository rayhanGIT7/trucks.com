<?php /** @var \App\Services\Fare $fare */ ?>
<table class="table table-sm mb-0">
    <tr><td>Distance</td><td class="text-end"><?= number_format($fare->distanceKm, 2) ?> km</td></tr>
    <tr><td>Base fare</td><td class="text-end"><?= money($fare->baseFare) ?></td></tr>
    <tr><td><?= number_format($fare->distanceKm, 2) ?> km × <?= money($fare->perKmRate) ?></td><td class="text-end"><?= money($fare->distanceCharge) ?></td></tr>
    <tr class="fw-bold fs-5"><td>Total</td><td class="text-end text-brand"><?= money($fare->total) ?></td></tr>
</table>
<?php if ($fare->total > $fare->baseFare + $fare->distanceCharge + 0.001): ?>
    <div class="form-text">Total includes minimum fare / rounding.</div>
<?php endif; ?>
