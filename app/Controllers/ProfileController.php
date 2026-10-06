<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Validator;
use App\Models\Booking;
use App\Models\User;

class ProfileController extends Controller
{
    public function show(): void
    {
        $user = $this->requireLogin();

        $this->view('profile/show', [
            'user'     => $user,
            'bookings' => count(Booking::forUser($user->id)),
        ]);
    }

    public function update(): void
    {
        $user = $this->requireLogin();

        $validator = new Validator($_POST, [
            'name'  => 'required|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'required|phone',
        ]);
        $errors = $validator->errors();

        if (!isset($errors['email']) && User::emailTaken($this->input('email'), $user->id)) {
            $errors['email'] = 'This email is used by another account.';
        }
        if (!isset($errors['phone']) && User::phoneTaken($this->input('phone'), $user->id)) {
            $errors['phone'] = 'This phone number is used by another account.';
        }
        if ($errors) {
            $this->backWithErrors($errors);
        }

        $user->update([
            'name'  => $this->input('name'),
            'email' => strtolower($this->input('email')),
            'phone' => $this->input('phone'),
        ]);

        $this->flash('success', 'Profile updated.');
        $this->redirect('/profile');
    }

    public function updatePassword(): void
    {
        $user = $this->requireLogin();

        $validator = new Validator($_POST, [
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);
        $errors = $validator->errors();

        if (!isset($errors['current_password']) && !password_verify($_POST['current_password'], $user->password_hash)) {
            $errors['current_password'] = 'Current password is wrong.';
        }
        if ($errors) {
            $this->backWithErrors($errors);
        }

        $user->changePassword($_POST['password']);

        $this->flash('success', 'Password changed.');
        $this->redirect('/profile');
    }
}
