<?php

namespace App\Controllers;

use App\Core\Controller;
use App\TruckApi\DummyTruckProvider;

/**
 * GET /api/dummy/trucks — the dummy truck API as JSON.
 * Shows the exact response format a real truck API should return.
 */
class DummyTruckApiController extends Controller
{
    public function index(): void
    {
        header('Content-Type: application/json');
        echo json_encode(['data' => (new DummyTruckProvider())->fetchTrucks()], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
