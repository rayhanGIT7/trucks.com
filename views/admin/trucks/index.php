<?php
/** @var \App\Models\Truck[] $trucks */
$title = 'Trucks';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="h4 mb-0">Trucks</h1>
    <div class="d-flex gap-2">
        <form method="post" action="<?= url('/admin/trucks/sync') ?>">
            <?= csrf_field() ?>
            <button class="btn btn-outline-primary"><i class="bi bi-arrow-repeat"></i> Sync from Truck API</button>
        </form>
        <a href="<?= url('/admin/trucks/create') ?>" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add truck</a>
    </div>
</div>
<p class="small text-muted">Trucks with an API id are updated on every sync (the API is the source of truth for them).</p>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
            <tr><th></th><th>Name</th><th>Type</th><th>Size / capacity</th><th class="text-end">Base fare</th><th class="text-end">Per km</th><th>API id</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
            <?php foreach ($trucks as $truck): ?>
                <tr>
                    <td><img src="<?= e($truck->imageUrl()) ?>" alt="" width="56" height="36" style="object-fit: contain"></td>
                    <td class="fw-semibold"><?= e($truck->name) ?></td>
                    <td><?= e($truck->typeLabel()) ?></td>
                    <td class="small"><?= e($truck->size_ft) ?> ft / <?= e($truck->capacity_ton) ?> ton</td>
                    <td class="text-end"><?= money($truck->base_fare) ?></td>
                    <td class="text-end"><?= money($truck->per_km_rate) ?></td>
                    <td class="small text-muted"><?= e($truck->external_id ?? 'manual') ?></td>
                    <td>
                        <form method="post" action="<?= url('/admin/trucks/' . $truck->id . '/toggle') ?>">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm <?= $truck->is_available ? 'btn-success' : 'btn-outline-secondary' ?>" title="Click to change">
                                <?= $truck->is_available ? 'Available' : 'Unavailable' ?>
                            </button>
                        </form>
                    </td>
                    <td><a href="<?= url('/admin/trucks/' . $truck->id . '/edit') ?>" class="btn btn-sm btn-outline-primary">Edit</a></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$trucks): ?>
                <tr><td colspan="9" class="text-center text-muted py-4">No trucks yet. Click <strong>Sync from Truck API</strong> to import them.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
