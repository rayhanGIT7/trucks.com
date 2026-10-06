<?php /** @var \App\Models\Payment[] $payments */ ?>
<div class="card shadow-sm">
    <div class="card-header bg-white fw-semibold">Payments</div>
    <?php if (!$payments): ?>
        <div class="card-body text-muted small">No payment attempts yet.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                <tr><th>Transaction</th><th>Amount</th><th>Method</th><th>Status</th><th>Date</th></tr>
                </thead>
                <tbody>
                <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td class="small"><?= e($payment->tran_id) ?></td>
                        <td><?= money($payment->amount) ?></td>
                        <td class="small"><?= e($payment->card_type ?? '-') ?></td>
                        <td><?= status_badge($payment->status) ?></td>
                        <td class="small"><?= format_date($payment->paid_at ?? $payment->created_at, 'd M Y, h:i A') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
