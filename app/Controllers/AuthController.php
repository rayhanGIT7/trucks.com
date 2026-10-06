<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function showRegister(): void
    {
        $this->view('auth/register');
    }

    public function register(): void
    {
        $validator = new Validator($_POST, [
            'name'     => 'required|max:100',
            'email'    => 'required|email|max:150',
            'phone'    => 'required|phone',
            'password' => 'required|min:8|confirmed',
        ]);
        $errors = $validator->errors();

        if (!isset($errors['email']) && User::emailTaken($this->input('email'))) {
            $errors['email'] = 'This email is already registered.';
        }
        if (!isset($errors['phone']) && User::phoneTaken($this->input('phone'))) {
            $errors['phone'] = 'This phone number is already registered.';
        }
        if ($errors) {
            $this->backWithErrors($errors);
        }

        $user = User::register($this->input('name'), $this->input('email'), $this->input('phone'), $_POST['password']);
        Auth::login($user);

        $this->flash('success', 'Welcome, ' . $user->name . '! Your account has been created.');
        $this->redirectAfterLogin();
    }

    public function showLogin(): void
    {
        $this->view('auth/login');
    }

    public function login(): void
    {
        $validator = new Validator($_POST, [
            'login'    => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            $this->backWithErrors($validator->errors());
        }

        if (!Auth::attempt($this->input('login'), $_POST['password'])) {
            $this->backWithErrors(['login' => 'Wrong email/phone or password.']);
        }

        $this->redirectAfterLogin();
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/');
    }

    /** Go to the page the user wanted before login, or a default page. */
    private function redirectAfterLogin(): void
    {
        $intended = Session::get('intended_url');
        Session::remove('intended_url');

        if ($intended) {
            $this->redirect($intended);
        }
        $this->redirect(Auth::isAdmin() ? '/admin' : '/');
    }
}
