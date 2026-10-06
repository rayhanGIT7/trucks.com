<?php

use App\Core\Auth;
use App\Core\Config;
use App\Core\View;

$menu = [
    '/admin/bookings'  => ['bi-journal-text', 'Bookings'],
    '/admin/payments'  => ['bi-credit-card', 'Payments'],
    '/admin/trucks'    => ['bi-truck', 'Trucks'],
    '/admin/locations' => ['bi-geo-alt', 'Locations'],
    '/admin/reports'   => ['bi-bar-chart', 'Reports'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Admin') ?> · <?= e(Config::get('app.name')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= url('/assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= url('/admin') ?>"><i class="bi bi-truck"></i> Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link <?= current_path() === '/admin' ? 'active' : '' ?>" href="<?= url('/admin') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <?php foreach ($menu as $path => [$icon, $label]): ?>
                    <li class="nav-item"><a class="nav-link <?= nav_active($path) ?>" href="<?= url($path) ?>"><i class="bi <?= $icon ?>"></i> <?= $label ?></a></li>
                <?php endforeach; ?>
            </ul>
            <span class="navbar-text me-3 small"><?= e(Auth::user()->name) ?></span>
            <a class="btn btn-outline-light btn-sm me-2" href="<?= url('/') ?>">View site</a>
            <form method="post" action="<?= url('/logout') ?>" class="d-inline">
                <?= csrf_field() ?>
                <button class="btn btn-outline-warning btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>

<main class="container-fluid py-4 px-lg-4">
    <?= partial('partials/flash') ?>
    <?= $content ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
