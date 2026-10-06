<?php

use App\Core\Session;

$message = Session::getFlash('message');
?>
<?php if ($message): ?>
    <div class="alert alert-<?= e($message['type']) ?> alert-dismissible fade show">
        <?= e($message['text']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
