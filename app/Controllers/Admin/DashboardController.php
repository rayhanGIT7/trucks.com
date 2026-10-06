<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Booking;
use App\Models\Truck;
use App\Models\User;
use App\Services\ReportService;

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();

        $this->view('admin/dashboard', [
            'thisMonth'      => (new ReportService())->summary(date('Y-m-01'), date('Y-m-d')),
            'customers'      => User::countCustomers(),
            'trucks'         => count(Truck::all()),
            'latestBookings' => Booking::latest(8),
        ], 'layouts/admin');
    }
}
