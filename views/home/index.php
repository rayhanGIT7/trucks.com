<?php $title = 'ShiftKoro — Book a truck for shifting'; $trip = null; ?>

<section class="hero text-center">
    <div class="container">
        <h1 class="display-5 fw-bold">Book a truck in minutes</h1>
        <p class="lead mb-0">Home or office shifting — choose your route, pick a truck, pay online. Done.</p>
    </div>
</section>

<div class="container">
    <div class="card shadow search-card border-0">
        <div class="card-body p-4">
            <?= partial('partials/trip-search', ['locations' => $locations, 'trip' => $trip]) ?>
        </div>
    </div>

    <h2 class="h4 text-center mt-5 mb-4">How it works</h2>
    <div class="row text-center g-4">
        <?php
        $steps = [
            ['bi-geo-alt', 'Choose route', 'Select pickup and destination. We calculate the distance.'],
            ['bi-truck', 'Pick a truck', 'See available trucks with the full fare upfront.'],
            ['bi-credit-card', 'Pay securely', 'Pay online with SSLCommerz (bKash, cards, banks).'],
            ['bi-check-circle', 'Confirmed', 'Your booking is confirmed instantly after payment.'],
        ];
        ?>
        <?php foreach ($steps as $i => [$icon, $heading, $text]): ?>
            <div class="col-6 col-md-3">
                <div class="step-icon mb-2"><i class="bi <?= $icon ?>"></i></div>
                <h3 class="h6 fw-bold"><?= $i + 1 ?>. <?= $heading ?></h3>
                <p class="small text-muted"><?= $text ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
