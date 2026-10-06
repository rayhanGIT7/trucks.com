<?php
/**
 * Pickup / destination / date search form.
 * @var \App\Models\Location[] $locations
 * @var \App\Services\Trip|null $trip  current search (to pre-select values)
 */
// Keep what the customer selected last time (default date: tomorrow).
$selectedPickup = (int) ($_GET['pickup'] ?? 0);
$selectedDropoff = (int) ($_GET['dropoff'] ?? 0);
$selectedDate = $_GET['date'] ?? date('Y-m-d', strtotime('+1 day'));
?>
<form method="get" action="<?= url('/trucks') ?>" class="row g-2 align-items-end">
    <div class="col-md-4">
        <label class="form-label small fw-semibold"><i class="bi bi-geo-alt text-success"></i> Pickup location</label>
        <select name="pickup" class="form-select" required>
            <option value="">Select pickup</option>
            <?php foreach ($locations as $location): ?>
                <option value="<?= $location->id ?>" <?= $location->id === $selectedPickup ? 'selected' : '' ?>><?= e($location->label()) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label small fw-semibold"><i class="bi bi-geo-alt-fill text-danger"></i> Destination</label>
        <select name="dropoff" class="form-select" required>
            <option value="">Select destination</option>
            <?php foreach ($locations as $location): ?>
                <option value="<?= $location->id ?>" <?= $location->id === $selectedDropoff ? 'selected' : '' ?>><?= e($location->label()) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <label class="form-label small fw-semibold"><i class="bi bi-calendar-event"></i> Shifting date</label>
        <input type="date" name="date" class="form-control" value="<?= e($selectedDate) ?>" min="<?= date('Y-m-d') ?>" required>
    </div>
    <div class="col-md-2 d-grid">
        <button class="btn btn-warning fw-semibold"><i class="bi bi-search"></i> Find trucks</button>
    </div>
</form>
