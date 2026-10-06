<?php

namespace App\Core;

/**
 * Wrapper around $_SESSION: flash messages, old form input and CSRF token.
 */
class Session
{
    /** Flash data saved by the previous request (available for this request only). */
    private static array $flash = [];

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_name('shiftkoro_session');
        session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
        session_start();

        self::$flash = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /** Save a value that will be readable on the NEXT request (after a redirect). */
    public static function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function getFlash(string $key, mixed $default = null): mixed
    {
        return self::$flash[$key] ?? $default;
    }

    public static function csrfToken(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }

    public static function isValidCsrf(string $token): bool
    {
        return !empty($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], $token);
    }

    public static function destroy(): void
    {
        $_SESSION = [];
        session_destroy();
    }
}
