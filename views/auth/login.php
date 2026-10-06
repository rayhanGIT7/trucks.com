<?php $title = 'Login'; ?>
<div class="container py-4" style="max-width: 440px">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h1 class="h4 mb-3">Login</h1>
            <form method="post" action="<?= url('/login') ?>" novalidate>
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Email or phone</label>
                    <input type="text" name="login" class="form-control <?= invalid('login') ?>" value="<?= e(old('login')) ?>" autofocus>
                    <div class="invalid-feedback"><?= e(error('login')) ?></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control <?= invalid('password') ?>">
                    <div class="invalid-feedback"><?= e(error('password')) ?></div>
                </div>
                <button class="btn btn-primary w-100">Login</button>
            </form>
            <p class="text-center small mt-3 mb-0">No account? <a href="<?= url('/register') ?>">Sign up</a></p>
        </div>
    </div>
</div>
