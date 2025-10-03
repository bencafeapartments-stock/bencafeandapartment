<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Rent;
use App\Models\Billing;
use App\Models\Apartment;
use App\Models\Maintenance;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class TenantController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $currentRent = $this->getCurrentRent($user);

        $data = [
            'user' => $user,
            'rent_status' => $currentRent ? $this->calculateRentStatus($currentRent) : 'no_rent',
            'next_due_date' => $currentRent ? $this->calculateNextDueDate($currentRent) : null,
            'pending_bills' => Billing::where('tenant_id', $user->id)
                ->whereIn('status', ['pending', 'overdue'])
                ->count(),
            'maintenance_requests' => Maintenance::where('tenant_id', $user->id)
                ->where('status', '!=', 'completed')
                ->count(),
            'recent_orders' => Order::where('tenant_id', $user->id)
                ->where('created_at', '>=', now()->subMonth())
                ->count(),
            'apartment_number' => $currentRent?->apartment->apartment_number ?? 'N/A',
            'monthly_rent' => $currentRent?->monthly_rent ?? 0,
        ];

        $recentActivity = $this->getRecentActivity($user);

        return view('tenant.dashboard', compact('data', 'recentActivity'));
    }

    public function viewRent()
    {
        $user = auth()->user();
        $currentRent = $this->getCurrentRent($user);

        if (!$currentRent) {
            return redirect()->route('tenant.dashboard')
                ->with('error', 'No active rental agreement found.');
        }

        $rentData = [
            'apartment_number' => $currentRent->apartment->apartment_number ?? 'N/A',
            'monthly_rent' => $currentRent->monthly_rent,
            'lease_start' => $currentRent->start_date,
            'lease_end' => $currentRent->end_date,
            'next_due_date' => $this->calculateNextDueDate($currentRent),
            'status' => $this->calculateRentStatus($currentRent),
        ];

        $paymentHistory = Billing::where('tenant_id', $user->id)
            ->where('billing_type', 'rent')
            ->orderBy('due_date', 'desc')
            ->limit(10)
            ->get()
            ->map(fn($bill) => [
                'month' => $bill->due_date->format('F Y'),
                'amount' => $bill->amount,
                'paid_date' => $bill->status === 'paid' ? $bill->updated_at->format('Y-m-d') : null,
                'status' => $bill->status === 'paid' ? 'paid' : 'pending',
            ])
            ->toArray();

        return view('tenant.rent.index', compact('rentData', 'paymentHistory'));
    }

    public function rentPayment()
    {
        $user = auth()->user();
        $currentRent = $this->getCurrentRent($user);

        if (!$currentRent) {
            return redirect()->route('tenant.dashboard')
                ->with('error', 'No active rental agreement found.');
        }

        $pendingBill = Billing::where('tenant_id', $user->id)
            ->where('billing_type', 'rent')
            ->whereIn('status', ['pending', 'overdue'])
            ->orderBy('due_date', 'asc')
            ->first();

        $lateFee = $this->calculateLateFee($pendingBill);

        $paymentData = [
            'apartment_number' => $currentRent->apartment->apartment_number,
            'monthly_rent' => $pendingBill?->amount ?? $currentRent->monthly_rent,
            'due_date' => $pendingBill?->due_date ?? $this->calculateNextDueDate($currentRent),
            'late_fee' => $lateFee,
            'total_amount' => ($pendingBill?->amount ?? $currentRent->monthly_rent) + $lateFee,
            'bill_id' => $pendingBill?->id,
        ];

        return view('tenant.rent.payment', compact('paymentData'));
    }

    public function payRent(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:gcash,bank_transfer,cash,card',
            'amount' => 'required|numeric|min:1',
        ]);

        return redirect()->route('tenant.rent.index')
            ->with('success', 'Rent payment submitted successfully!');
    }

    public function rentHistory()
    {
        $user = auth()->user();

        $payments = Billing::where('tenant_id', $user->id)
            ->where('billing_type', 'rent')
            ->where('status', 'paid')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(fn($bill) => [
                'date' => $bill->updated_at->format('Y-m-d'),
                'amount' => $bill->amount,
                'method' => 'Not specified',
                'status' => 'completed',
            ])
            ->toArray();

        return view('tenant.rent.history', compact('payments'));
    }

    public function viewMaintenance()
    {
        $user = auth()->user();

        $maintenanceRequests = Maintenance::where('tenant_id', $user->id)
            ->with('apartment')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($maintenance) => [
                'id' => $maintenance->id,
                'issue' => $maintenance->issue_description,
                'status' => $maintenance->status,
                'submitted' => $maintenance->created_at->format('Y-m-d'),
                'priority' => $maintenance->priority,
            ])
            ->toArray();

        return view('tenant.maintenance.index', compact('maintenanceRequests'));
    }

    public function createMaintenanceRequest()
    {
        $priorities = ['low', 'medium', 'high'];
        $categories = ['plumbing', 'electrical', 'appliances', 'security', 'cleaning', 'other'];

        return view('tenant.maintenance.create', compact('priorities', 'categories'));
    }

    public function storeMaintenanceRequest(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'issue_description' => 'required|string|min:10',
            'preferred_time' => 'nullable|string',
        ]);

        $user = auth()->user();
        $currentRent = $this->getCurrentRent($user);

        if (!$currentRent) {
            return back()->with('error', 'No active rental agreement found. Cannot submit maintenance request.')
                ->withInput();
        }

        $maintenance = Maintenance::create([
            'tenant_id' => $user->id,
            'apartment_id' => $currentRent->apartment_id,
            'issue_description' => $request->issue_description,
            'priority' => $request->priority,
            'category' => $request->category,
            'status' => 'pending',
            'staff_notes' => $request->preferred_time ? 'Preferred time: ' . $request->preferred_time : null,
        ]);

        return redirect()->route('tenant.maintenance.index')
            ->with('success', "Maintenance request #{$maintenance->id} submitted successfully!");
    }

    public function showMaintenance($id)
    {
        $maintenanceRecord = Maintenance::where('tenant_id', auth()->id())
            ->findOrFail($id);

        $maintenance = [
            'id' => $maintenanceRecord->id,
            'issue' => $maintenanceRecord->issue_description,
            'category' => $maintenanceRecord->category,
            'priority' => $maintenanceRecord->priority,
            'status' => $maintenanceRecord->status,
            'submitted_date' => $maintenanceRecord->created_at->format('Y-m-d'),
            'description' => $maintenanceRecord->issue_description,
            'staff_notes' => $maintenanceRecord->staff_notes ?? 'No staff notes yet.',
        ];

        return view('tenant.maintenance.show', compact('maintenance'));
    }

    public function viewOrders()
    {
        $orders = Order::where('tenant_id', auth()->id())
            ->with(['orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($order) => [
                'id' => $order->id,
                'items' => $order->orderItems
                    ->map(fn($item) => "{$item->quantity}x {$item->product->product_name}")
                    ->join(', '),
                'total' => $order->amount,
                'status' => $order->status,
                'date' => $order->created_at->format('Y-m-d'),
            ])
            ->toArray();

        return view('tenant.orders.index', compact('orders'));
    }

    public function createOrder()
    {
        return redirect()->route('tenant.menu');
    }

    public function viewMenu()
    {
        $menuItems = Product::where('is_available', true)
            ->get()
            ->groupBy('category')
            ->map(fn($products, $category) => [
                'category' => ucfirst($category ?: 'General'),
                'items' => $products->map(fn($product) => [
                    'id' => $product->id,
                    'name' => $product->product_name,
                    'price' => $product->price,
                    'description' => $product->description ?? 'No description available',
                    'prep_time' => $product->prep_time_minutes,
                    'ingredients' => $product->ingredients,
                    'image_url' => $product->image_url,
                ])->toArray()
            ])
            ->values()
            ->toArray();

        return view('tenant.menu', compact('menuItems'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'special_instructions' => 'nullable|string|max:500',
            'order_type' => 'required|in:pickup,delivery',
        ]);

        $user = auth()->user();
        $currentRent = $this->getCurrentRent($user);

        if (!$currentRent) {
            return back()->with('error', 'No active rental agreement found. Cannot place order.')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                $product = Product::where('id', $item['product_id'])
                    ->where('is_available', true)
                    ->first();

                if (!$product) {
                    throw new \Exception('One or more items are unavailable.');
                }

                $quantity = (int) $item['quantity'];
                $itemTotal = $product->price * $quantity;
                $totalAmount += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'total_price' => $itemTotal,
                ];
            }

            $orderTypeText = $request->order_type === 'delivery' ? '(Delivery)' : '(Pickup)';
            $instructions = trim(($request->special_instructions ?? '') . ' ' . $orderTypeText);

            $order = Order::create([
                'tenant_id' => $user->id,
                'amount' => $totalAmount,
                'delivery_fee' => 0,
                'status' => 'pending',
                'special_instructions' => $instructions,
                'ordered_at' => now(),
            ]);

            foreach ($orderItems as $item) {
                OrderItem::create(array_merge(['order_id' => $order->id], $item));
            }

            DB::commit();

            $deliveryText = $request->order_type === 'delivery' ? 'for delivery' : 'for pickup';
            return redirect()->route('tenant.orders.show', $order->id)
                ->with('success', "Order #{$order->id} placed successfully {$deliveryText}! Total: ₱" . number_format($totalAmount, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function cancelOrder($id)
    {
        $order = Order::where('tenant_id', auth()->id())
            ->where('id', $id)
            ->where('status', 'pending')
            ->first();

        if (!$order) {
            return redirect()->route('tenant.orders.index')
                ->with('error', 'Order not found or cannot be cancelled.');
        }

        $order->update([
            'status' => 'cancelled',
            'special_instructions' => $order->special_instructions . "\n\nCancelled: " . now()->format('Y-m-d H:i:s')
        ]);

        return redirect()->route('tenant.orders.index')
            ->with('success', "Order #{$order->id} cancelled successfully.");
    }

    public function reorderItems($id)
    {
        $originalOrder = Order::where('tenant_id', auth()->id())
            ->where('id', $id)
            ->with('orderItems.product')
            ->first();

        if (!$originalOrder) {
            return redirect()->route('tenant.orders.index')
                ->with('error', 'Original order not found.');
        }

        $unavailable = $originalOrder->orderItems
            ->filter(fn($item) => !$item->product || !$item->product->is_available)
            ->pluck('product.product_name')
            ->filter()
            ->toArray();

        if (!empty($unavailable)) {
            return redirect()->route('tenant.menu')
                ->with('error', 'Unavailable items: ' . implode(', ', $unavailable));
        }

        try {
            DB::beginTransaction();

            $totalAmount = $originalOrder->orderItems->sum(fn($item) => $item->product->price * $item->quantity);

            $newOrder = Order::create([
                'tenant_id' => auth()->id(),
                'amount' => $totalAmount,
                'delivery_fee' => 0,
                'status' => 'pending',
                'special_instructions' => "Reorder of #{$originalOrder->id}",
                'ordered_at' => now(),
            ]);

            foreach ($originalOrder->orderItems as $item) {
                OrderItem::create([
                    'order_id' => $newOrder->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->price,
                    'total_price' => $item->product->price * $item->quantity,
                ]);
            }

            DB::commit();

            return redirect()->route('tenant.orders.show', $newOrder->id)
                ->with('success', "Items reordered! New Order #{$newOrder->id}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reorder items.');
        }
    }

    public function showOrder($id)
    {
        $orderRecord = Order::where('tenant_id', auth()->id())
            ->with(['orderItems.product'])
            ->findOrFail($id);

        $order = [
            'id' => $orderRecord->id,
            'items' => $orderRecord->orderItems->map(fn($item) => [
                'name' => $item->product->product_name,
                'price' => $item->unit_price,
                'quantity' => $item->quantity,
            ])->toArray(),
            'subtotal' => $orderRecord->amount,
            'delivery_fee' => $orderRecord->delivery_fee ?? 0,
            'total' => $orderRecord->amount + ($orderRecord->delivery_fee ?? 0),
            'status' => $orderRecord->status,
            'order_date' => $orderRecord->created_at->format('Y-m-d H:i:s'),
            'delivered_date' => $orderRecord->delivered_at?->format('Y-m-d H:i:s'),
            'special_instructions' => $orderRecord->special_instructions ?? 'No special instructions',
        ];

        return view('tenant.orders.show', compact('order'));
    }

    public function viewBills()
    {
        $bills = Billing::where('tenant_id', auth()->id())
            ->orderBy('due_date', 'desc')
            ->get()
            ->map(fn($bill) => [
                'id' => $bill->id,
                'type' => ucfirst(str_replace('_', ' ', $bill->billing_type)),
                'amount' => $bill->amount,
                'due_date' => $bill->due_date->format('Y-m-d'),
                'status' => $bill->status,
            ])
            ->toArray();

        return view('tenant.billing.index', compact('bills'));
    }

    public function showBill($id)
    {
        $billing = Billing::where('tenant_id', auth()->id())->findOrFail($id);

        $bill = [
            'id' => $billing->id,
            'type' => ucfirst(str_replace('_', ' ', $billing->billing_type)),
            'amount' => $billing->amount,
            'due_date' => $billing->due_date->format('Y-m-d'),
            'status' => $billing->status,
            'description' => $billing->description,
            'issued_date' => $billing->created_at->format('Y-m-d'),
            'late_fee' => $this->calculateLateFee($billing),
        ];

        return view('tenant.billing.show', compact('bill'));
    }

    public function payBill(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|in:gcash,bank_transfer,cash,card',
        ]);

        return redirect()->route('tenant.billing.index')
            ->with('success', 'Bill payment submitted successfully!');
    }

    public function profile()
    {
        return view('tenant.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'contact_number' => 'nullable|string|max:20',
        ]);

        $user->update($request->only(['name', 'email', 'contact_number']));

        return redirect()->route('tenant.profile')
            ->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('tenant.profile')
            ->with('success', 'Password updated successfully!');
    }

    private function getCurrentRent($user)
    {
        return Rent::where('tenant_id', $user->id)
            ->where('status', 'active')
            ->with('apartment')
            ->first();
    }

    private function calculateNextDueDate($rent)
    {
        $today = now();
        $currentMonth = $today->copy()->startOfMonth();
        $nextMonth = $today->copy()->addMonth()->startOfMonth();

        $currentBill = Billing::where('tenant_id', $rent->tenant_id)
            ->where('billing_type', 'rent')
            ->whereYear('due_date', $today->year)
            ->whereMonth('due_date', $today->month)
            ->first();

        return (!$currentBill || $currentBill->status !== 'paid') ? $currentMonth : $nextMonth;
    }

    private function calculateRentStatus($rent)
    {
        $today = now();

        $overdueBills = Billing::where('tenant_id', $rent->tenant_id)
            ->where('billing_type', 'rent')
            ->where('status', '!=', 'paid')
            ->where('due_date', '<', $today)
            ->exists();

        if ($overdueBills) {
            return 'overdue';
        }

        $currentBill = Billing::where('tenant_id', $rent->tenant_id)
            ->where('billing_type', 'rent')
            ->whereYear('due_date', $today->year)
            ->whereMonth('due_date', $today->month)
            ->first();

        return ($currentBill && $currentBill->status === 'paid') ? 'current' : 'pending';
    }

    private function getRecentActivity($user)
    {
        $activities = collect();
        $cutoff = now()->subMonth();

        Billing::where('tenant_id', $user->id)
            ->where('status', 'paid')
            ->where('updated_at', '>=', $cutoff)
            ->orderBy('updated_at', 'desc')
            ->take(3)
            ->get()
            ->each(fn($payment) => $activities->push([
                'type' => 'payment',
                'message' => ucfirst($payment->billing_type) . ' payment - ₱' . number_format($payment->amount, 0),
                'time' => $payment->updated_at->diffForHumans(),
                'icon' => '💰'
            ]));

        Order::where('tenant_id', $user->id)
            ->where('created_at', '>=', $cutoff)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->each(fn($order) => $activities->push([
                'type' => 'order',
                'message' => 'Cafe order - ₱' . number_format($order->amount, 0),
                'time' => $order->created_at->diffForHumans(),
                'icon' => '☕'
            ]));

        Maintenance::where('tenant_id', $user->id)
            ->where('updated_at', '>=', $cutoff)
            ->orderBy('updated_at', 'desc')
            ->take(2)
            ->get()
            ->each(fn($maintenance) => $activities->push([
                'type' => 'maintenance',
                'message' => ($maintenance->status === 'completed' ? 'Completed: ' : 'Request: ')
                    . substr($maintenance->issue_description, 0, 50) . '...',
                'time' => $maintenance->updated_at->diffForHumans(),
                'icon' => '🔧'
            ]));

        return $activities->sortByDesc('time')->take(5)->values()->toArray();
    }

    private function calculateLateFee($bill)
    {
        if (!$bill || $bill->status === 'paid' || $bill->due_date >= now()) {
            return 0;
        }

        $daysOverdue = now()->diffInDays($bill->due_date);

        if ($daysOverdue <= 5) {
            return 0;
        }

        $lateFeePercentage = min(10, 5 + ($daysOverdue - 5) * 0.5);
        return $bill->amount * ($lateFeePercentage / 100);
    }
}
