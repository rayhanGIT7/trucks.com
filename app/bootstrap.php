<?php

use App\Core\Config;

// Autoloader: App\Services\FareCalculator → app/Services/FareCalculator.php
spl_autoload_register(function (string $class) {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $file = __DIR__ . '/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

require __DIR__ . '/helpers.php';

$configFile = __DIR__ . '/../config/config.php';
if (!is_file($configFile)) {
    exit('Missing config/config.php — copy config/config.example.php to config/config.php');
}
Config::load($configFile);

date_default_timezone_set(Config::get('app.timezone', 'Asia/Dhaka'));
error_reporting(E_ALL);
ini_set('display_errors', Config::get('app.debug') ? '1' : '0');
