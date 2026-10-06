<?php

// Small global helpers used mostly inside views.

use App\Core\Config;
use App\Core\Session;

/** Escape text for safe HTML output. Use it for EVERY value printed in a view. */
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Full URL for a path inside the app, e.g. url('/trucks'). */
function url(string $path = '/', array $query = []): string
{
    $url = rtrim(Config::get('app.url'), '/') . '/' . ltrim($path, '/');
    return $query ? $url . '?' . http_build_query($query) : $url;
}

/**
 * Current path inside the app, without the base folder.
 * e.g. http://localhost/shiftkoro/trucks?x=1 → "/trucks" (or "/trucks?x=1" with $withQuery)
 */
function current_path(bool $withQuery = false): string
{
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $path = parse_url($uri, PHP_URL_PATH) ?: '/';

    $base = rtrim((string) parse_url(Config::get('app.url'), PHP_URL_PATH), '/');
    if ($base !== '' && str_starts_with($path, $base)) {
        $path = substr($path, strlen($base));
    }
    $path = '/' . trim($path, '/');

    $query = parse_url($uri, PHP_URL_QUERY);
    return $withQuery && $query ? "$path?$query" : $path;
}

/** "active" CSS class for the current menu item. */
function nav_active(string $path): string
{
    $current = current_path();
    $isActive = $path === '/' ? $current === '/' : str_starts_with($current, $path);
    return $isActive ? 'active' : '';
}

/** URL of a file in public/assets, or the image URL itself if it is already absolute. */
function asset(string $path): string
{
    return preg_match('#^https?://#', $path) ? $path : url($path);
}

/**
 * Print a small reusable piece of a page from views/.
 * Example: partial('partials/flash')  or  partial('partials/fare-table', ['fare' => $fare])
 */
function partial(string $template, array $data = []): string
{
    return \App\Core\View::capture($template, $data);
}

/** Hidden CSRF field. Put it inside every POST form. */
function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(Session::csrfToken()) . '">';
}

/** Value the user typed before a failed form submit. */
function old(string $field, mixed $default = ''): string
{
    $old = Session::getFlash('old', []);
    return (string) ($old[$field] ?? $default);
}

/** Validation error message for a field (or empty string). */
function error(string $field): string
{
    $errors = Session::getFlash('errors', []);
    return $errors[$field] ?? '';
}

/** "is-invalid" CSS class when a field has an error (Bootstrap). */
function invalid(string $field): string
{
    return error($field) !== '' ? 'is-invalid' : '';
}

function money(float|string|null $amount): string
{
    return '৳' . number_format((float) $amount, 2);
}

function format_date(?string $date, string $format = 'd M Y'): string
{
    return $date ? date($format, strtotime($date)) : '-';
}

/** Bootstrap badge colour for booking/payment statuses. */
function status_badge(string $status): string
{
    $colors = [
        'pending'   => 'warning',
        'initiated' => 'warning',
        'confirmed' => 'success',
        'success'   => 'success',
        'completed' => 'primary',
        'cancelled' => 'secondary',
        'failed'    => 'danger',
    ];
    $color = $colors[$status] ?? 'secondary';
    return '<span class="badge text-bg-' . $color . '">' . e(ucfirst($status)) . '</span>';
}
