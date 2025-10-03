<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Apartment;
use App\Models\Rent;
use App\Models\Billing;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_staff' => User::staff()->active()->count(),
            'total_tenants' => User::tenants()->active()->count(),
            'total_apartments' => 0,
            'monthly_revenue' => 0,
            'occupancy_rate' => 85,
            'pending_maintenance' => 5,
        ];

        $recentActivities = [
            ['type' => 'staff', 'message' => 'New staff member added', 'time' => '2 hours ago'],
            ['type' => 'payment', 'message' => 'Rent payment received', 'time' => '5 hours ago'],
            ['type' => 'maintenance', 'message' => 'Maintenance request completed', 'time' => '1 day ago'],
        ];

        return view('owner.dashboard', compact('stats', 'recentActivities'));
    }



}
