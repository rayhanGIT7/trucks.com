<?php $title = 'Sign up'; ?>
<div class="container py-4" style="max-width: 520px">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h1 class="h4 mb-3">Create your account</h1>
            <form method="post" action="<?= url('/register') ?>" novalidate>
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Full name</label>
                    <input type="text" name="name" class="form-control <?= invalid('name') ?>" value="<?= e(old('name')) ?>">
                    <div class="invalid-feedback"><?= e(error('name')) ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control <?= invalid('email') ?>" value="<?= e(old('email')) ?>">
                        <div class="invalid-feedback"><?= e(error('email')) ?></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mobile number</label>
                        <input type="tel" name="phone" class="form-control <?= invalid('phone') ?>" value="<?= e(old('phone')) ?>" placeholder="01XXXXXXXXX">
                        <div class="invalid-feedback"><?= e(error('phone')) ?></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control <?= invalid('password') ?>">
                        <div class="invalid-feedback"><?= e(error('password')) ?></div>
                        <div class="form-text">At least 8 characters.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Confirm password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>
                <button class="btn btn-primary w-100">Sign up</button>
            </form>
            <p class="text-center small mt-3 mb-0">Already registered? <a href="<?= url('/login') ?>">Login</a></p>
        </div>
    </div>
</div>
