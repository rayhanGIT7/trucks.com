<?php

// Every URL of the application in one place.

use App\Controllers\Admin;
use App\Controllers\AuthController;
use App\Controllers\BookingController;
use App\Controllers\DummyTruckApiController;
use App\Controllers\HomeController;
use App\Controllers\PaymentController;
use App\Controllers\ProfileController;
use App\Controllers\TruckController;
use App\Core\Router;

$router = new Router();

// Public pages
$router->get('/', [HomeController::class, 'index']);
$router->get('/trucks', [TruckController::class, 'index']);
$router->get('/trucks/{id}', [TruckController::class, 'show']);

// Auth
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

// Profile
$router->get('/profile', [ProfileController::class, 'show']);
$router->post('/profile', [ProfileController::class, 'update']);
$router->post('/profile/password', [ProfileController::class, 'updatePassword']);

// Bookings
$router->get('/bookings', [BookingController::class, 'index']);
$router->get('/bookings/create', [BookingController::class, 'create']);
$router->post('/bookings', [BookingController::class, 'store']);
$router->get('/bookings/{id}', [BookingController::class, 'show']);
$router->post('/bookings/{id}/pay', [BookingController::class, 'pay']);
$router->post('/bookings/{id}/cancel', [BookingController::class, 'cancel']);

// SSLCommerz callbacks (called by SSLCommerz, not by our forms)
$router->callback('/payment/success', [PaymentController::class, 'success']);
$router->callback('/payment/fail', [PaymentController::class, 'fail']);
$router->callback('/payment/cancel', [PaymentController::class, 'cancel']);
$router->callback('/payment/ipn', [PaymentController::class, 'ipn']);

// Dummy truck API
$router->get('/api/dummy/trucks', [DummyTruckApiController::class, 'index']);

// Admin panel
$router->get('/admin', [Admin\DashboardController::class, 'index']);

$router->get('/admin/trucks', [Admin\TruckController::class, 'index']);
$router->get('/admin/trucks/create', [Admin\TruckController::class, 'create']);
$router->post('/admin/trucks', [Admin\TruckController::class, 'store']);
$router->post('/admin/trucks/sync', [Admin\TruckController::class, 'sync']);
$router->get('/admin/trucks/{id}/edit', [Admin\TruckController::class, 'edit']);
$router->post('/admin/trucks/{id}', [Admin\TruckController::class, 'update']);
$router->post('/admin/trucks/{id}/toggle', [Admin\TruckController::class, 'toggle']);

$router->get('/admin/locations', [Admin\LocationController::class, 'index']);
$router->get('/admin/locations/create', [Admin\LocationController::class, 'create']);
$router->post('/admin/locations', [Admin\LocationController::class, 'store']);
$router->get('/admin/locations/{id}/edit', [Admin\LocationController::class, 'edit']);
$router->post('/admin/locations/{id}', [Admin\LocationController::class, 'update']);

$router->get('/admin/bookings', [Admin\BookingController::class, 'index']);
$router->get('/admin/bookings/{id}', [Admin\BookingController::class, 'show']);
$router->post('/admin/bookings/{id}/status', [Admin\BookingController::class, 'updateStatus']);

$router->get('/admin/payments', [Admin\PaymentController::class, 'index']);
$router->get('/admin/reports', [Admin\ReportController::class, 'index']);

return $router;
