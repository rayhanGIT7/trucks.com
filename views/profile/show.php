<?php
/** @var \App\Models\User $user */
$title = 'My profile';
?>
<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <i class="bi bi-person-circle display-3 text-brand"></i>
                    <h1 class="h5 mt-2 mb-0"><?= e($user->name) ?></h1>
                    <p class="text-muted small"><?= e($user->email) ?> · <?= e($user->phone) ?></p>
                    <p class="mb-1"><strong><?= $bookings ?></strong> bookings</p>
                    <p class="small text-muted mb-3">Member since <?= format_date($user->created_at) ?></p>
                    <a href="<?= url('/bookings') ?>" class="btn btn-outline-primary btn-sm">Booking history</a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">Personal information</div>
                <div class="card-body">
                    <form method="post" action="<?= url('/profile') ?>" novalidate>
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control <?= invalid('name') ?>" value="<?= e(old('name', $user->name)) ?>">
                                <div class="invalid-feedback"><?= e(error('name')) ?></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control <?= invalid('email') ?>" value="<?= e(old('email', $user->email)) ?>">
                                <div class="invalid-feedback"><?= e(error('email')) ?></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Mobile</label>
                                <input type="tel" name="phone" class="form-control <?= invalid('phone') ?>" value="<?= e(old('phone', $user->phone)) ?>">
                                <div class="invalid-feedback"><?= e(error('phone')) ?></div>
                            </div>
                        </div>
                        <button class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">Change password</div>
                <div class="card-body">
                    <form method="post" action="<?= url('/profile/password') ?>" novalidate>
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Current password</label>
                                <input type="password" name="current_password" class="form-control <?= invalid('current_password') ?>">
                                <div class="invalid-feedback"><?= e(error('current_password')) ?></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">New password</label>
                                <input type="password" name="password" class="form-control <?= invalid('password') ?>">
                                <div class="invalid-feedback"><?= e(error('password')) ?></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Confirm new password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                        <button class="btn btn-outline-primary">Change password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
