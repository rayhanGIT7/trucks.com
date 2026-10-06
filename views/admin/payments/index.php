<?php

use App\Models\Payment;

/**
 * @var Payment[] $payments
 * @var string $status
 * @var string $keyword
 */
$title = 'Payments';
?>
<h1 class="h4 mb-3">Payments</h1>

<form method="get" class="row g-2 mb-3">
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">All statuses</option>
            <?php foreach (Payment::STATUSES as $option): ?>
                <option value="<?= $option ?>" <?= $option === $status ? 'selected' : '' ?>><?= ucfirst($option) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-5">
        <input type="search" name="q" value="<?= e($keyword) ?>" class="form-control" placeholder="Transaction id, bank transaction id or booking no">
    </div>
    <div class="col-md-2 d-grid"><button class="btn btn-primary">Filter</button></div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
            <tr><th>Transaction</th><th>Booking</th><th>Customer</th><th class="text-end">Amount</th><th>Method</th><th>Bank tran id</th><th>Status</th><th>Date</th></tr>
            </thead>
            <tbody>
            <?php foreach ($payments as $payment): ?>
                <tr>
                    <td class="small"><?= e($payment->tran_id) ?></td>
                    <td class="small"><a href="<?= url('/admin/bookings/' . $payment->booking_id) ?>"><?= e($payment->booking_no) ?></a></td>
                    <td class="small"><?= e($payment->customer_name) ?></td>
                    <td class="text-end"><?= money($payment->amount) ?></td>
                    <td class="small"><?= e($payment->card_type ?? '-') ?></td>
                    <td class="small"><?= e($payment->bank_tran_id ?? '-') ?></td>
                    <td><?= status_badge($payment->status) ?></td>
                    <td class="small"><?= format_date($payment->paid_at ?? $payment->created_at, 'd M Y, h:i A') ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$payments): ?>
                <tr><td colspan="8" class="text-center text-muted py-4">No payments found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
