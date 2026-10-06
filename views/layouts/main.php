<?php

use App\Core\Auth;
use App\Core\Config;

$authUser = Auth::user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? Config::get('app.name')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= url('/assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-brand">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= url('/') ?>"><i class="bi bi-truck"></i> <?= e(Config::get('app.name')) ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link <?= nav_active('/') ?>" href="<?= url('/') ?>">Home</a></li>
                <li class="nav-item"><a class="nav-link <?= nav_active('/trucks') ?>" href="<?= url('/trucks') ?>">Trucks</a></li>
                <?php if ($authUser): ?>
                    <li class="nav-item"><a class="nav-link <?= nav_active('/bookings') ?>" href="<?= url('/bookings') ?>">My Bookings</a></li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if ($authUser): ?>
                    <?php if ($authUser->isAdmin()): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= url('/admin') ?>"><i class="bi bi-speedometer2"></i> Admin</a></li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"><i class="bi bi-person-circle"></i> <?= e($authUser->name) ?></a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= url('/profile') ?>">Profile</a></li>
                            <li><a class="dropdown-item" href="<?= url('/bookings') ?>">Booking history</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="post" action="<?= url('/logout') ?>">
                                    <?= csrf_field() ?>
                                    <button class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link <?= nav_active('/login') ?>" href="<?= url('/login') ?>">Login</a></li>
                    <li class="nav-item"><a class="btn btn-warning btn-sm ms-lg-2 mt-1" href="<?= url('/register') ?>">Sign up</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="flex-grow-1">
    <div class="container mt-3">
        <?= partial('partials/flash') ?>
    </div>
    <?= $content ?>
</main>

<footer class="bg-dark text-white-50 py-4 mt-5">
    <div class="container d-flex flex-column flex-md-row justify-content-between gap-2 small">
        <span>&copy; <?= date('Y') ?> <?= e(Config::get('app.name')) ?> — Hassle-free shifting</span>
        <span><i class="bi bi-telephone"></i> 01700-000000 &nbsp; <i class="bi bi-envelope"></i> support@shiftkoro.com</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
