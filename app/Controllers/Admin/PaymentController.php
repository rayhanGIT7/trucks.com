<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();
        $status = in_array($this->input('status'), Payment::STATUSES, true) ? $this->input('status') : '';

        $this->view('admin/payments/index', [
            'payments' => Payment::search($status, $this->input('q')),
            'status'   => $status,
            'keyword'  => $this->input('q'),
        ], 'layouts/admin');
    }
}
