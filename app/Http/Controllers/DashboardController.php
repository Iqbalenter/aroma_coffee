<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboard) {}

    public function index()
    {
        $stats = $this->dashboard->stats();
        $monthlySales = $this->dashboard->monthlySales();
        $satisfaction = $this->dashboard->satisfactionDistribution();

        return view('dashboard', compact('stats', 'monthlySales', 'satisfaction'));
    }
}
