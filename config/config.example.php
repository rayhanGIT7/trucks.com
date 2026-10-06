<?php

// Copy this file to config/config.php and change the values.
// config/config.php is git-ignored, so passwords never go into git.

return [
    'app' => [
        'name'  => 'ShiftKoro',
        // Full URL of the "public" folder, without trailing slash.
        // XAMPP example: http://localhost/shiftkoro/public
        // php -S example: http://localhost:8000
        'url'   => 'http://localhost:8000',
        'debug' => true, // show error details. Set false in production.
        'timezone' => 'Asia/Dhaka',
    ],

    'db' => [
        'host'     => '127.0.0.1',
        'port'     => 3306,
        'name'     => 'shiftkoro',
        'user'     => 'root',
        'password' => '',
    ],

    // Fare rules. Per-truck base fare and per-km rate are stored in the trucks table.
    'fare' => [
        'minimum_fare' => 1000, // a booking never costs less than this (BDT)
        'round_to'     => 10,   // round total up to nearest 10 taka
    ],

    'distance' => [
        // Straight-line distance × road_factor ≈ real road distance.
        'road_factor' => 1.3,
    ],

    // Where truck data comes from.
    //   'dummy' → app/TruckApi/dummy-trucks.json
    //   'http'  → real REST API at base_url (GET {base_url}/trucks)
    'truck_api' => [
        'driver'   => 'dummy',
        'base_url' => '',
        'api_key'  => '',
    ],

    'sslcommerz' => [
        'store_id'       => 'your_store_id',
        'store_password' => 'your_store_password',
        'sandbox'        => true, // false = live payments
        'currency'       => 'BDT',
        // Keep true. Set false ONLY on a local PC whose PHP has no CA certificates.
        'verify_ssl'     => true,
    ],
];
