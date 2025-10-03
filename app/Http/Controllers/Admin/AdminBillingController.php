<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\User;
use App\Models\Rent;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminBillingController extends Controller
{

    public function createBill()
    {
        $tenants = User::tenants()->active()->with('tenantrents.apartment')->get();

        return view('owner.billing.create', compact('tenants'));
    }

    public function generateMonthlyRentBills(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
            'due_day' => 'required|integer|min:1|max:31',
        ]);

        $month = Carbon::parse($request->month . '-01');
        $dueDate = $month->copy()->addMonth()->day($request->due_day);


        $activerents = Rent::where('status', 'active')
            ->with(['tenant', 'apartment'])
            ->get();

        $generatedCount = 0;

        foreach ($activerents as $rent) {

            $existingBill = Billing::where('rent_id', $rent->id)
                ->where('billing_type', 'rent')
                ->whereMonth('due_date', $dueDate->month)
                ->whereYear('due_date', $dueDate->year)
                ->first();

            if (!$existingBill) {
                Billing::generateRentBill($rent, $dueDate);
                $generatedCount++;
            }
        }

        return redirect()->route('owner.billing.index')
            ->with('success', "Generated {$generatedCount} rent bills for " . $month->format('F Y'));
    }

    public function sendPaymentReminders(Request $request)
    {
        $request->validate([
            'bill_ids' => 'required|array',
            'bill_ids.*' => 'exists:billing,id',
            'message' => 'nullable|string|max:500',
        ]);

        $bills = Billing::whereIn('id', $request->bill_ids)
            ->with('tenant')
            ->get();

        $sentCount = $bills->count();

        return redirect()->back()
            ->with('success', "Payment reminders sent to {$sentCount} tenants.");
    }

    public function exportBills(Request $request)
    {
        $query = Billing::with(['tenant', 'rent.apartment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('billing_type')) {
            $query->where('billing_type', $request->billing_type);
        }

        if ($request->filled('month')) {
            $month = Carbon::parse($request->month);
            $query->whereMonth('due_date', $month->month)
                ->whereYear('due_date', $month->year);
        }

        $bills = $query->get();


        $filename = 'bills_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($bills) {
            $file = fopen('php://output', 'w');


            fputcsv($file, [
                'Bill ID',
                'Tenant Name',
                'Apartment',
                'Billing Type',
                'Amount',
                'Late Fee',
                'Total Amount',
                'Due Date',
                'Status',
                'Description'
            ]);

            foreach ($bills as $bill) {
                fputcsv($file, [
                    $bill->id,
                    $bill->tenant->name,
                    $bill->rent ? $bill->rent->apartment->apartment_number : 'N/A',
                    $bill->billing_type_label,
                    $bill->amount,
                    $bill->late_fee,
                    $bill->total_amount,
                    $bill->due_date->format('Y-m-d'),
                    $bill->status_label,
                    $bill->description
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function updateOverdueBills()
    {
        $pendingBills = Billing::where('status', 'pending')
            ->where('due_date', '<', now())
            ->get();

        foreach ($pendingBills as $bill) {
            $bill->markAsOverdue();
        }

        return redirect()->back()
            ->with('success', "Updated {$pendingBills->count()} overdue bills.");
    }

    public function getBillingStats()
    {
        $stats = [
            'total_revenue' => Billing::where('status', 'paid')->sum('amount'),
            'monthly_revenue' => Billing::where('status', 'paid')
                ->whereMonth('due_date', now()->month)
                ->whereYear('due_date', now()->year)
                ->sum('amount'),
            'paid_bills' => Billing::where('status', 'paid')->count(),
            'pending_bills' => Billing::where('status', 'pending')->count(),
            'overdue_bills' => Billing::where('status', 'overdue')->count(),
            'cancelled_bills' => Billing::where('status', 'cancelled')->count(),
        ];

        return response()->json($stats);
    }

    public function storeBill(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:users,id',
            'billing_type' => 'required|in:rent,utilities,maintenance,cafe,other',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after:today',
            'description' => 'nullable|string|max:1000',
            'rent_id' => 'nullable|exists:rent,id',
        ]);

        $billing = Billing::create([
            'tenant_id' => $request->tenant_id,
            'rent_id' => $request->rent_id,
            'billing_type' => $request->billing_type,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'issued_date' => now(),
            'status' => 'pending',
            'description' => $request->description,
            'late_fee' => 0,
        ]);

        return redirect()->route('owner.billing.index')
            ->with('success', 'Bill created successfully.');
    }
    public function showBill(Billing $billing)
    {
        $billing->load(['tenant', 'rent.apartment', 'order', 'rentPayments']);

        return view('owner.billing.show', compact('billing'));
    }
    public function editBill(Billing $billing)
    {
        if ($billing->status === 'paid') {
            return redirect()->back()->with('error', 'Cannot edit paid bills.');
        }

        $tenants = User::tenants()->active()->get();

        return view('owner.billing.edit', compact('billing', 'tenants'));
    }
    public function updateBill(Request $request, Billing $billing)
    {
        if ($billing->status === 'paid') {
            return redirect()->back()->with('error', 'Cannot edit paid bills.');
        }

        $request->validate([
            'tenant_id' => 'required|exists:users,id',
            'billing_type' => 'required|in:rent,utilities,maintenance,cafe,other',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'description' => 'nullable|string|max:1000',
        ]);

        $billing->update([
            'tenant_id' => $request->tenant_id,
            'billing_type' => $request->billing_type,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'description' => $request->description,
        ]);

        return redirect()->route('owner.billing.index')
            ->with('success', 'Bill updated successfully.');
    }

    public function manageBilling(Request $request)
    {
        $query = Billing::with(['tenant', 'rent.apartment', 'order']);


        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('billing_type')) {
            $query->where('billing_type', $request->billing_type);
        }

        if ($request->filled('month')) {
            $month = Carbon::parse($request->month);
            $query->whereMonth('due_date', $month->month)
                ->whereYear('due_date', $month->year);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('tenant', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('rent.apartment', function ($q) use ($search) {
                $q->where('apartment_number', 'like', "%{$search}%");
            });
        }

        $bills = $query->orderBy('due_date', 'desc')->paginate(15);

        $stats = [
            'total_revenue' => Billing::where('status', 'paid')->sum('amount'),
            'paid_bills' => Billing::where('status', 'paid')->count(),
            'pending_bills' => Billing::where('status', 'pending')->count(),
            'overdue_bills' => Billing::where('status', 'overdue')->count(),
        ];

        return view('owner.billing.index', compact('bills', 'stats'));
    }





}
