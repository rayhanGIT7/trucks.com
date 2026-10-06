<?php
/** @var \App\Models\Location $location  (new Location() when adding) */
$isNew = $location->id === null;
$title = $isNew ? 'Add location' : 'Edit ' . $location->name;
$action = $isNew ? url('/admin/locations') : url('/admin/locations/' . $location->id);
$fields = [
    'name'      => ['Area name', $location->name],
    'city'      => ['City', $location->city ?: 'Dhaka'],
    'latitude'  => ['Latitude', $isNew ? '' : (string) $location->latitude],
    'longitude' => ['Longitude', $isNew ? '' : (string) $location->longitude],
];
?>
<a href="<?= url('/admin/locations') ?>" class="small"><i class="bi bi-arrow-left"></i> Locations</a>
<h1 class="h4 my-3"><?= e($title) ?></h1>

<div class="card shadow-sm" style="max-width: 640px">
    <div class="card-body">
        <form method="post" action="<?= $action ?>" novalidate>
            <?= csrf_field() ?>
            <div class="row">
                <?php foreach ($fields as $field => [$label, $value]): ?>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><?= $label ?></label>
                        <input type="text" name="<?= $field ?>" class="form-control <?= invalid($field) ?>" value="<?= e(old($field, $value)) ?>">
                        <div class="invalid-feedback"><?= e(error($field)) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            <p class="form-text">Tip: right-click a place in Google Maps to copy its latitude, longitude.</p>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" <?= $location->is_active ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_active">Show to customers</label>
            </div>
            <button class="btn btn-primary"><?= $isNew ? 'Add location' : 'Save changes' ?></button>
        </form>
    </div>
</div>
