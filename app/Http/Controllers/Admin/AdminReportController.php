<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Apartment;
use App\Models\Billing;

class AdminReportController extends Controller
{
    public function viewReports()
    {
        $reportData = [
            'monthly_revenue' => 125000,
            'occupancy_rate' => 85,
            'maintenance_cost' => 8500,
            'cafe_revenue' => 35000,
        ];

        return view('owner.reports.index', compact('reportData'));
    }

    public function revenueReport()
    {

        return view('owner.reports.revenue');
    }

    public function occupancyReport()
    {

        return view('owner.reports.occupancy');
    }

    public function downloadReports()
    {

        return response()->download(storage_path('app/reports/monthly_report.pdf'));
    }
}
