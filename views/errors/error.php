<?php $title = "Error $code"; ?>
<div class="container py-5 text-center">
    <div class="display-1 fw-bold text-brand"><?= e($code) ?></div>
    <p class="lead"><?= e($message) ?></p>
    <a href="<?= url('/') ?>" class="btn btn-primary">Back to home</a>
</div>
