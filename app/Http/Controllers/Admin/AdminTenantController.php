<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Rent;
use App\Models\Billing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminTenantController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'tenant')
            ->with(['tenantRents.apartment', 'billings']);

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }

        }

        if ($request->filled('payment_status')) {
            switch ($request->payment_status) {
                case 'overdue':
                    $query->whereHas('billings', function ($q) {
                        $q->where('status', 'overdue');
                    });
                    break;
                case 'current':
                    $query->whereDoesntHave('billings', function ($q) {
                        $q->whereIn('status', ['overdue', 'pending']);
                    });
                    break;
                case 'at_risk':
                    $query->whereHas('billings', function ($q) {
                        $q->where('status', 'overdue');
                    }, '>=', 3);
                    break;
            }
        }


        if ($request->filled('lease_status')) {
            switch ($request->lease_status) {
                case 'active':
                    $query->whereHas('tenantRents', function ($q) {
                        $q->where('status', 'active')
                            ->where(function ($subQ) {
                                $subQ->whereNull('end_date')
                                    ->orWhere('end_date', '>', now()->addDays(30));
                            });
                    });
                    break;
                case 'expiring':
                    $query->whereHas('tenantRents', function ($q) {
                        $q->where('status', 'active')
                            ->whereBetween('end_date', [now(), now()->addDays(30)]);
                    });
                    break;
                case 'expired':
                    $query->whereHas('tenantRents', function ($q) {
                        $q->where('status', 'active')
                            ->where('end_date', '<', now());
                    });
                    break;
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        $tenants = $query->latest()->paginate(15)->appends($request->query());

        $stats = [
            'total_tenants' => User::where('role', 'tenant')->count(),
            'active_tenants' => User::where('role', 'tenant')->where('is_active', true)->count(),
            'inactive_tenants' => User::where('role', 'tenant')->where('is_active', false)->count(),
            'overdue_tenants' => User::where('role', 'tenant')
                ->where('is_active', true)
                ->whereHas('billings', function ($q) {
                    $q->where('status', 'overdue');
                })->count(),
            'outstanding_amount' => Billing::whereIn('status', ['pending', 'overdue'])
                ->whereHas('tenant', function ($q) {
                    $q->where('role', 'tenant');
                })->sum('amount'),
            'expiring_leases' => Rent::where('status', 'active')
                ->whereBetween('end_date', [now(), now()->addDays(30)])
                ->count(),
        ];

        return view('owner.tenants.index', compact('tenants', 'stats'));
    }
}

