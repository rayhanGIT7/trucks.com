<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Location;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home/index', [
            'locations' => Location::active(),
        ]);
    }
}
