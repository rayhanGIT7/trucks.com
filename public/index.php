<?php

// Front controller: every request enters here.

use App\Core\Config;
use App\Core\HttpException;
use App\Core\Session;
use App\Core\View;

require __DIR__ . '/../app/bootstrap.php';

// php -S: let the built-in server serve real files (css, images) directly.
if (PHP_SAPI === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

$router = require __DIR__ . '/../app/routes.php';

try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], current_path());
} catch (Throwable $e) {
    $code = $e instanceof HttpException ? $e->getStatusCode() : 500;
    $message = $e->getMessage();

    if ($code === 500) {
        error_log((string) $e);
        $message = Config::get('app.debug')
            ? get_class($e) . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine()
            : 'Something went wrong. Please try again later.';
    }

    if (!headers_sent()) {
        Session::start(); // the layout needs the logged-in user
    }
    http_response_code($code);
    View::render('errors/error', ['code' => $code, 'message' => $message]);
}
