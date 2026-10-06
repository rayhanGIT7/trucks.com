<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Services\ReportService;

class ReportController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();

        // Default range: this month. Bad dates fall back to the default.
        $from = $this->validDate($this->input('from'), date('Y-m-01'));
        $to = $this->validDate($this->input('to'), date('Y-m-d'));
        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }

        $reports = new ReportService();

        $this->view('admin/reports/index', [
            'from'       => $from,
            'to'         => $to,
            'summary'    => $reports->summary($from, $to),
            'revenue'    => $reports->revenueByDay($from, $to),
            'topTrucks'  => $reports->topTrucks($from, $to),
            'topRoutes'  => $reports->topRoutes($from, $to),
        ], 'layouts/admin');
    }

    private function validDate(string $value, string $default): string
    {
        $date = \DateTime::createFromFormat('Y-m-d', $value);
        return $date && $date->format('Y-m-d') === $value ? $value : $default;
    }
}
