<?php

use App\Models\Truck;

/** @var Truck $truck  (new Truck() when adding) */
$isNew = $truck->id === null;
$title = $isNew ? 'Add truck' : 'Edit ' . $truck->name;
$action = $isNew ? url('/admin/trucks') : url('/admin/trucks/' . $truck->id);
?>
<a href="<?= url('/admin/trucks') ?>" class="small"><i class="bi bi-arrow-left"></i> Trucks</a>
<h1 class="h4 my-3"><?= e($title) ?></h1>

<?php if ($truck->external_id): ?>
    <div class="alert alert-info small">This truck comes from the Truck API (id <?= e($truck->external_id) ?>). Changes will be overwritten by the next sync.</div>
<?php endif; ?>

<div class="card shadow-sm" style="max-width: 820px">
    <div class="card-body">
        <form method="post" action="<?= $action ?>" novalidate>
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control <?= invalid('name') ?>" value="<?= e(old('name', $truck->name)) ?>">
                    <div class="invalid-feedback"><?= e(error('name')) ?></div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select <?= invalid('type') ?>">
                        <?php foreach (Truck::TYPES as $type): ?>
                            <option value="<?= $type ?>" <?= old('type', $truck->type) === $type ? 'selected' : '' ?>><?= ucwords(str_replace('_', ' ', $type)) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback"><?= e(error('type')) ?></div>
                </div>
            </div>
            <div class="row">
                <?php
                $numberFields = [
                    'size_ft'      => 'Size (feet)',
                    'capacity_ton' => 'Capacity (ton)',
                    'base_fare'    => 'Base fare (৳)',
                    'per_km_rate'  => 'Rate per km (৳)',
                ];
                ?>
                <?php foreach ($numberFields as $field => $label): ?>
                    <div class="col-6 col-md-3 mb-3">
                        <label class="form-label"><?= $label ?></label>
                        <input type="number" step="0.01" min="0" name="<?= $field ?>" class="form-control <?= invalid($field) ?>"
                               value="<?= e(old($field, $isNew ? '' : (string) $truck->$field)) ?>">
                        <div class="invalid-feedback"><?= e(error($field)) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Image URL</label>
                <input type="text" name="image_url" class="form-control <?= invalid('image_url') ?>" value="<?= e(old('image_url', $truck->image_url ?? '')) ?>" placeholder="/assets/images/trucks/9.svg or https://...">
                <div class="invalid-feedback"><?= e(error('image_url')) ?></div>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control <?= invalid('description') ?>"><?= e(old('description', $truck->description ?? '')) ?></textarea>
                <div class="invalid-feedback"><?= e(error('description')) ?></div>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_available" id="is_available" value="1" <?= $truck->is_available ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_available">Available for booking</label>
            </div>
            <button class="btn btn-primary"><?= $isNew ? 'Add truck' : 'Save changes' ?></button>
        </form>
    </div>
</div>
