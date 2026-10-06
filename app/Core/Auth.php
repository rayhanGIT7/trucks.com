<?php

namespace App\Core;

use App\Models\User;

/**
 * Who is logged in. The user id is kept in the session.
 */
class Auth
{
    private static ?User $user = null;

    /** Log in with email or phone + password. Returns false if wrong. */
    public static function attempt(string $login, string $password): bool
    {
        $user = User::findByEmailOrPhone($login);
        if ($user === null || !password_verify($password, $user->password_hash)) {
            return false;
        }

        self::login($user);
        return true;
    }

    public static function login(User $user): void
    {
        session_regenerate_id(true); // prevents session fixation
        Session::set('user_id', $user->id);
        self::$user = $user;
    }

    public static function logout(): void
    {
        self::$user = null;
        Session::destroy();
    }

    public static function user(): ?User
    {
        if (self::$user === null && isset($_SESSION['user_id'])) {
            self::$user = User::find((int) $_SESSION['user_id']);
        }
        return self::$user;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function isAdmin(): bool
    {
        $user = self::user();
        return $user !== null && $user->isAdmin();
    }
}
