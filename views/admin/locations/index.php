<?php
/** @var \App\Models\Location[] $locations */
$title = 'Locations';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Locations</h1>
    <a href="<?= url('/admin/locations/create') ?>" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add location</a>
</div>
<p class="small text-muted">Customers choose pickup and destination from this list. Coordinates are used to calculate the distance.</p>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
            <tr><th>Name</th><th>City</th><th>Latitude</th><th>Longitude</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
            <?php foreach ($locations as $location): ?>
                <tr>
                    <td class="fw-semibold"><?= e($location->name) ?></td>
                    <td><?= e($location->city) ?></td>
                    <td class="small"><?= e($location->latitude) ?></td>
                    <td class="small"><?= e($location->longitude) ?></td>
                    <td><?= $location->is_active ? '<span class="badge text-bg-success">Active</span>' : '<span class="badge text-bg-secondary">Hidden</span>' ?></td>
                    <td><a href="<?= url('/admin/locations/' . $location->id . '/edit') ?>" class="btn btn-sm btn-outline-primary">Edit</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
