<?php

namespace App\Core;

use App\Models\User;

/**
 * Base class for all controllers: rendering, redirects, input and access checks.
 */
abstract class Controller
{
    protected function view(string $template, array $data = [], string $layout = 'layouts/main'): void
    {
        View::render($template, $data, $layout);
    }

    /** Send the browser to another page and stop the script. */
    protected function redirect(string $path, array $query = []): void
    {
        header('Location: ' . url($path, $query));
        exit;
    }

    /** Go back to the previous page (used after a failed form submit). */
    protected function back(): void
    {
        $previous = $_SERVER['HTTP_REFERER'] ?? url('/');
        header('Location: ' . $previous);
        exit;
    }

    /** Go back to the form, showing validation errors and the old input. */
    protected function backWithErrors(array $errors): void
    {
        $old = $_POST;
        unset($old['_token'], $old['password'], $old['password_confirmation'], $old['current_password']);

        Session::flash('errors', $errors);
        Session::flash('old', $old);
        $this->back();
    }

    /** Show a green/red message on the next page. */
    protected function flash(string $type, string $message): void
    {
        Session::flash('message', ['type' => $type, 'text' => $message]);
    }

    /** Trimmed value from POST or GET. */
    protected function input(string $key, string $default = ''): string
    {
        return trim((string) ($_POST[$key] ?? $_GET[$key] ?? $default));
    }

    protected function requireLogin(): User
    {
        if (!Auth::check()) {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                Session::set('intended_url', current_path(true)); // come back here after login
            }
            $this->flash('warning', 'Please log in to continue.');
            $this->redirect('/login');
        }
        return Auth::user();
    }

    protected function requireAdmin(): User
    {
        $user = $this->requireLogin();
        if (!$user->isAdmin()) {
            throw new HttpException(403);
        }
        return $user;
    }
}
