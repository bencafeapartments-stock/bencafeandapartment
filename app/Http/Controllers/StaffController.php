<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Maintenance;
use App\Models\Apartment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\BillingItem;
use App\Models\Rent;
use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;
use App\Mail\ReceiptMail;
use Illuminate\Support\Facades\Storage;


class StaffController extends Controller
{


    public function dashboard()
    {
        $stats = [
            'pending_maintenance' => Maintenance::where('status', 'pending')->count(),
            'new_orders' => Order::where('status', 'pending')->count(),
            'today_billing' => Billing::whereDate('created_at', today())->sum('amount'),
            'active_tenants' => User::tenants()->active()->count(),
        ];

        $todayTasks = [
            ['priority' => 'high', 'task' => 'Fix plumbing in Apt 205', 'time' => '2 hours ago'],
            ['priority' => 'medium', 'task' => 'Process 5 pending cafe orders', 'time' => '30 minutes'],
            ['priority' => 'low', 'task' => 'Send billing reminders', 'time' => '3 tenants overdue'],
        ];

        return view('staff.dashboard', compact('stats', 'todayTasks'));
    }

    public function manageMaintenance()
    {
        $maintenanceRequests = Maintenance::with('tenant', 'apartment')
            ->latest()
            ->get()
            ->map(function ($maintenance) {
                return [
                    'id' => $maintenance->id,
                    'apartment' => $maintenance->apartment->apartment_number ?? 'N/A',
                    'issue' => $maintenance->issue_description,
                    'priority' => $maintenance->priority,
                    'status' => $maintenance->status,
                    'tenant_name' => $maintenance->tenant->name ?? 'Unknown',
                    'created_at' => $maintenance->created_at,
                    'cost' => $maintenance->formatted_cost,
                ];
            });

        return view('staff.maintenance.index', compact('maintenanceRequests'));
    }

    public function createMaintenance()
    {
        $tenants = User::tenants()->active()->get();
        $apartments = Apartment::all();
        return view('staff.maintenance.create', compact('tenants', 'apartments'));
    }

    public function storeMaintenance(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:users,id',
            'apartment_id' => 'required|exists:apartments,id',
            'issue_description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'nullable|string',
        ]);

        Maintenance::create([
            'tenant_id' => $request->tenant_id,
            'apartment_id' => $request->apartment_id,
            'issue_description' => $request->issue_description,
            'priority' => $request->priority,
            'category' => $request->category ?? 'general',
            'status' => 'pending',
            'staff_id' => auth()->id(),
        ]);

        return redirect()->route('staff.maintenance.index')->with('success', 'Maintenance request created successfully.');
    }

    public function completeMaintenance($id)
    {
        $maintenance = Maintenance::findOrFail($id);

        $maintenance->markCompleted(
            null,
            'Completed by staff member: ' . auth()->user()->name
        );

        return redirect()->route('staff.maintenance.index')->with('success', 'Maintenance request completed.');
    }

    public function manageOrders()
    {
        $orders = Order::with('tenant', 'orderItems.product')
            ->latest()
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'tenant' => $order->tenant->name,
                    'items' => $order->orderItems->map(function ($item) {
                        return $item->quantity . 'x ' . $item->product->product_name;
                    })->join(', '),
                    'total' => $order->amount,
                    'status' => $order->status,
                    'created_at' => $order->created_at,
                    'formatted_amount' => '₱' . number_format($order->amount, 2),
                    'has_bill' => $order->billing_id ? true : false,
                    'bill_id' => $order->billing_id,
                ];
            });

        return view('staff.orders.index', compact('orders'));
    }
    public function showOrder($id)
    {
        $orderRecord = Order::with(['tenant', 'orderItems.product'])
            ->findOrFail($id);


        $order = [
            'id' => $orderRecord->id,
            'tenant' => [
                'id' => $orderRecord->tenant->id,
                'name' => $orderRecord->tenant->name,
                'email' => $orderRecord->tenant->email,
                'contact_number' => $orderRecord->tenant->contact_number,
            ],
            'items' => $orderRecord->orderItems->map(function ($item) {
                return [
                    'name' => $item->product->product_name,
                    'price' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'total' => $item->total_price,
                    'category' => $item->product->category ?? 'General',
                ];
            })->toArray(),
            'subtotal' => $orderRecord->amount,
            'delivery_fee' => $orderRecord->delivery_fee ?? 0,
            'total' => $orderRecord->amount + ($orderRecord->delivery_fee ?? 0),
            'status' => $orderRecord->status,
            'order_date' => $orderRecord->created_at->format('Y-m-d H:i:s'),
            'special_instructions' => $orderRecord->special_instructions,
            'staff_notes' => $orderRecord->staff_notes ?? null,
            'ordered_at' => $orderRecord->ordered_at ? $orderRecord->ordered_at->format('Y-m-d H:i:s') : null,
            'prepared_at' => $orderRecord->prepared_at ? $orderRecord->prepared_at->format('Y-m-d H:i:s') : null,
            'delivered_at' => $orderRecord->delivered_at ? $orderRecord->delivered_at->format('Y-m-d H:i:s') : null,
            'has_bill' => $orderRecord->billing_id ? true : false,
            'bill_id' => $orderRecord->billing_id,
        ];

        return view('staff.orders.show', compact('order'));
    }


    public function createOrder()
    {
        $tenants = User::where('role', 'tenant')
            ->where('is_active', true)
            ->get();

        $menuItems = Product::all()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->product_name,
                    'price' => $product->price,
                    'category' => $product->category ?? 'general',
                ];
            })->toArray();

        return view('staff.orders.create', compact('tenants', 'menuItems'));
    }
    public function storeOrder(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'total_amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:paid,unpaid',
        ]);

        DB::transaction(function () use ($request) {

            $totalQuantity = count($request->items);


            $order = Order::create([
                'tenant_id' => $request->tenant_id,
                'amount' => $request->total_amount,
                'quantity' => $totalQuantity,
                'delivery_fee' => 0,
                'status' => $request->payment_status,
                'staff_id' => auth()->id(),
                'ordered_at' => now(),
            ]);


            $itemCounts = array_count_values($request->items);


            foreach ($itemCounts as $itemName => $quantity) {
                $product = Product::where('product_name', $itemName)->first();

                if ($product) {
                    $unitPrice = $product->price ?? 0;
                    $totalPrice = $unitPrice * $quantity;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                    ]);


                    \Log::info("Created order item: {$itemName}, Price: {$unitPrice}, Quantity: {$quantity}");
                } else {

                    \Log::error("Product not found: {$itemName}");


                    throw new \Exception("Product '{$itemName}' not found in database");
                }
            }


            if ($request->payment_status === 'unpaid') {
                $itemNames = array_keys($itemCounts);
                $description = 'Cafe order #' . $order->id . ' - ' .
                    implode(', ', array_slice($itemNames, 0, 3)) .
                    (count($itemNames) > 3 ? '...' : '');

                $bill = Billing::create([
                    'tenant_id' => $request->tenant_id,
                    'billing_type' => 'cafe',
                    'amount' => $request->total_amount,
                    'due_date' => now()->addDays(7),
                    'description' => $description,
                    'status' => 'pending',
                    'created_by' => auth()->id(),
                ]);


                $order->update(['billing_id' => $bill->id]);
            }
        });

        return redirect()->route('staff.orders.index')->with('success', 'Order created successfully.');
    }

    public function getUnpaidOrders($tenantId)
    {
        try {
            $orders = Order::where('tenant_id', $tenantId)
                ->where('status', 'unpaid')
                ->with(['orderItems.product'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($order) {

                    $items = $order->orderItems->map(function ($item) {
                        return $item->quantity . 'x ' . $item->product->product_name;
                    })->join(', ');

                    return [
                        'id' => $order->id,
                        'amount' => $order->amount,
                        'quantity' => $order->quantity,
                        'items' => $items,
                        'created_at' => $order->created_at->format('M d, Y g:i A'),
                        'status' => $order->status,
                    ];
                });

            return response()->json($orders);

        } catch (\Exception $e) {
            \Log::error('Error fetching unpaid orders', [
                'tenant_id' => $tenantId,
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Failed to load orders'], 500);
        }
    }
    public function markOrderPaid($id)
    {
        $order = Order::findOrFail($id);

        DB::transaction(function () use ($order) {

            $order->update(['status' => 'paid']);

            if ($order->billing_id) {
                $bill = Billing::find($order->billing_id);
                if ($bill && $bill->status !== 'paid') {
                    $bill->update([
                        'status' => 'paid',
                        'paid_date' => now(),
                    ]);
                }
            }
        });

        return redirect()->route('staff.orders.index')->with('success', 'Order marked as paid successfully.');
    }
    public function markOrderUnpaid($id)
    {
        $order = Order::findOrFail($id);

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'unpaid']);

            if (!$order->billing_id) {
                $itemsDescription = $order->orderItems->take(3)->map(function ($item) {
                    return $item->quantity . 'x ' . $item->product->product_name;
                })->join(', ');

                if ($order->orderItems->count() > 3) {
                    $itemsDescription .= '...';
                }

                $bill = Billing::create([
                    'tenant_id' => $order->tenant_id,
                    'billing_type' => 'cafe',
                    'amount' => $order->amount,
                    'due_date' => now()->addDays(7),
                    'description' => 'Cafe order #' . $order->id . ' - ' . $itemsDescription,
                    'status' => 'pending',
                    'created_by' => auth()->id(),
                ]);

                $order->update(['billing_id' => $bill->id]);
            } else {
                $bill = Billing::find($order->billing_id);
                if ($bill) {
                    $bill->update([
                        'status' => 'pending',
                        'paid_date' => null,
                    ]);
                }
            }
        });

        return redirect()->route('staff.orders.index')->with('success', 'Order marked as unpaid and bill created.');
    }

    public function completeOrder($id)
    {
        $order = Order::findOrFail($id);

        $order->markAsDelivered();

        return redirect()->route('staff.orders.index')->with('success', 'Order marked as delivered.');
    }

    public function startPreparingOrder($id)
    {
        $order = Order::findOrFail($id);

        $order->markAsPreparing(auth()->id());

        return redirect()->route('staff.orders.index')->with('success', 'Order preparation started.');
    }

    public function markOrderReady($id)
    {
        $order = Order::findOrFail($id);

        $order->markAsReady();

        return redirect()->route('staff.orders.index')->with('success', 'Order marked as ready for pickup.');
    }


    public function manageMenuItems()
    {
        $menuItems = Product::latest()->get();
        return view('staff.menu-items.index', compact('menuItems'));
    }

    public function createMenuItem()
    {
        return view('staff.menu-items.create');
    }

    public function storeMenuItem(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|boolean',
        ]);

        $data = $request->only(['product_name', 'description', 'price', 'category', 'is_active']);


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/menu'), $imageName);
            $data['image_url'] = 'images/menu/' . $imageName;
        }

        Product::create($data);

        return redirect()->route('staff.menu-items.index')->with('success', 'Menu item created successfully.');
    }

    public function editMenuItem($id)
    {
        $menuItem = Product::findOrFail($id);
        return view('staff.menu-items.edit', compact('menuItem'));
    }

    public function updateMenuItem(Request $request, $id)
    {
        $menuItem = Product::findOrFail($id);

        $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|boolean',
        ]);

        $data = $request->only(['product_name', 'description', 'price', 'category', 'is_active']);


        if ($request->hasFile('image')) {

            if ($menuItem->image_url && file_exists(public_path($menuItem->image_url))) {
                unlink(public_path($menuItem->image_url));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/menu'), $imageName);
            $data['image_url'] = 'images/menu/' . $imageName;
        }

        $menuItem->update($data);

        return redirect()->route('staff.menu-items.index')->with('success', 'Menu item updated successfully.');
    }

    public function destroyMenuItem($id)
    {
        $menuItem = Product::findOrFail($id);

        $ordersCount = OrderItem::where('product_id', $id)->count();

        if ($ordersCount > 0) {
            return redirect()->route('staff.menu-items.index')
                ->with('error', 'Cannot delete this menu item as it has been used in orders. You can set it as inactive instead.');
        }


        if ($menuItem->image_url && file_exists(public_path($menuItem->image_url))) {
            unlink(public_path($menuItem->image_url));
        }

        $menuItem->delete();

        return redirect()->route('staff.menu-items.index')->with('success', 'Menu item deleted successfully.');
    }

    public function manageBilling()
    {
        $bills = Billing::with(['tenant', 'rent.apartment'])
            ->latest()
            ->get()
            ->map(function ($bill) {
                return [
                    'id' => $bill->id,
                    'tenant_name' => $bill->tenant ? $bill->tenant->name : 'Unknown',
                    'tenant_email' => $bill->tenant ? $bill->tenant->email : '',
                    'billing_type_label' => ucfirst(str_replace('_', ' ', $bill->billing_type)),
                    'apartment_number' => $bill->rent && $bill->rent->apartment ? $bill->rent->apartment->apartment_number : null,
                    'billing_month' => $bill->due_date ? $bill->due_date->format('M Y') : '',
                    'formatted_amount' => '₱' . number_format($bill->amount, 2),
                    'due_date' => $bill->due_date,
                    'status' => $bill->status,
                    'description' => $bill->description,
                ];
            });

        return view('staff.billing.index', compact('bills'));
    }


    public function manageBillingWithArrays()
    {
        $bills = Billing::with(['tenant', 'rent.apartment'])
            ->latest()
            ->get()
            ->map(function ($bill) {
                return [
                    'id' => $bill->id,
                    'tenant_name' => $bill->tenant ? $bill->tenant->name : 'Unknown',
                    'tenant_email' => $bill->tenant ? $bill->tenant->email : '',
                    'billing_type_label' => ucfirst(str_replace('_', ' ', $bill->billing_type)),
                    'apartment_number' => $bill->rent && $bill->rent->apartment ? $bill->rent->apartment->apartment_number : 'N/A',
                    'amount' => $bill->amount,
                    'formatted_amount' => '₱' . number_format($bill->amount, 2),
                    'due_date' => $bill->due_date,
                    'status' => $bill->status,
                    'created_at' => $bill->created_at,
                    'billing_month' => $bill->due_date ? $bill->due_date->format('M Y') : '',
                    'description' => $bill->description,
                ];
            });

        $stats = [
            'total_revenue' => Billing::where('status', 'paid')->sum('amount'),
            'paid_bills' => Billing::where('status', 'paid')->count(),
            'pending_bills' => Billing::where('status', 'pending')->count(),
            'overdue_bills' => Billing::where('status', 'overdue')->count(),
        ];

        return view('staff.billing.index', compact('bills', 'stats'));
    }

    public function createBill()
    {
        $tenants = User::tenants()->active()->get();
        $billTypes = ['rent', 'utilities', 'cafe', 'maintenance', 'other'];

        return view('staff.billing.create', compact('tenants', 'billTypes'));
    }


    public function storeBill(Request $request)
    {
        $validationRules = [
            'tenant_id' => 'required|exists:users,id',
            'billing_types' => 'required|array|min:1',
            'billing_types.*' => 'string|in:rent,utilities,maintenance,cafe,parking,other',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after:today',
            'description' => 'nullable|string',
            'billing_breakdown' => 'nullable|json',
            'amounts' => 'nullable|array',
            'amounts.*' => 'numeric|min:0',
            'descriptions' => 'nullable|array',
            'descriptions.*' => 'string|nullable',
            'rent_id' => 'nullable|exists:rent,id',
        ];


        if ($request->has('billing_types') && in_array('cafe', $request->billing_types)) {
            $validationRules['selected_orders'] = 'nullable|json';
        }

        $request->validate($validationRules);

        try {
            DB::transaction(function () use ($request) {

                $bill = Billing::create([
                    'tenant_id' => $request->tenant_id,
                    'billing_type' => implode(',', $request->billing_types),
                    'amount' => $request->amount,
                    'due_date' => $request->due_date,
                    'description' => $request->description,
                    'status' => 'pending',
                    'created_by' => auth()->id(),
                    'billing_breakdown' => $request->billing_breakdown,
                    'rent_id' => $request->rent_id,
                ]);


                if (in_array('cafe', $request->billing_types) && $request->selected_orders) {
                    $selectedOrderIds = json_decode($request->selected_orders, true);

                    if (is_array($selectedOrderIds) && !empty($selectedOrderIds)) {

                        Order::whereIn('id', $selectedOrderIds)
                            ->where('tenant_id', $request->tenant_id)
                            ->where('status', 'unpaid')
                            ->update(['billing_id' => $bill->id]);

                        \Log::info('Cafe orders linked to consolidated bill', [
                            'bill_id' => $bill->id,
                            'order_ids' => $selectedOrderIds,
                            'tenant_id' => $request->tenant_id,
                            'billing_types' => $request->billing_types
                        ]);
                    }
                }


                if (in_array('rent', $request->billing_types) && $request->rent_id) {

                    \Log::info('Rent billing created', [
                        'bill_id' => $bill->id,
                        'rent_id' => $request->rent_id,
                        'tenant_id' => $request->tenant_id
                    ]);
                }

                if ($request->billing_breakdown) {
                    $breakdown = json_decode($request->billing_breakdown, true);

                    foreach ($breakdown as $type => $item) {
                        if ($item['amount'] > 0) {

                            BillingItem::create([
                                'billing_id' => $bill->id,
                                'type' => $type,
                                'amount' => $item['amount'],
                                'description' => $item['description']
                            ]);
                        }
                    }
                }
            });

            return redirect()->route('staff.billing.index')
                ->with('success', 'Consolidated bill created successfully with ' . count($request->billing_types) . ' billing type(s).');

        } catch (\Exception $e) {
            \Log::error('Error creating consolidated bill', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'billing_types' => $request->billing_types ?? []
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create bill: ' . $e->getMessage()]);
        }
    }
    public function emailInvoice($billId)
    {
        try {
            $bill = Billing::with(['tenant', 'rent.apartment', 'billingItems'])
                ->findOrFail($billId);

            $invoiceNumber = 'INV-' . str_pad($bill->id, 6, '0', STR_PAD_LEFT);

            $invoiceData = [
                'invoice_number' => $invoiceNumber,
                'invoice_date' => $bill->created_at->format('F d, Y'),
                'due_date' => $bill->due_date->format('F d, Y'),
                'tenant' => [
                    'name' => $bill->tenant->name,
                    'email' => $bill->tenant->email,
                    'contact' => $bill->tenant->contact_number ?? '',
                    'apartment' => $bill->rent && $bill->rent->apartment
                        ? $bill->rent->apartment->apartment_number
                        : 'N/A'
                ],
                'items' => $this->getInvoiceItems($bill),
                'subtotal' => $bill->amount,
                'tax' => 0,
                'total' => $bill->amount,
                'status' => $bill->status,
                'payment_terms' => 'Payment due within 7 days of invoice date',
                'notes' => $bill->description ?? '',
            ];


            $pdf = app('dompdf.wrapper');
            $pdf->loadView('staff.billing.invoice-pdf', compact('invoiceData'));


            $pdfPath = storage_path('app/temp/' . $invoiceNumber . '.pdf');
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }
            $pdf->save($pdfPath);
            Mail::to($bill->tenant->email)->send(new InvoiceMail($invoiceData, $pdfPath));


            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }

            return redirect()->back()->with('success', 'Invoice sent to ' . $bill->tenant->email);

        } catch (\Exception $e) {
            \Log::error('Failed to send invoice email', [
                'bill_id' => $billId,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Failed to send invoice: ' . $e->getMessage());
        }
    }

    public function emailReceipt($billId)
    {
        try {
            $bill = Billing::with(['tenant', 'rent.apartment', 'billingItems'])
                ->findOrFail($billId);

            if ($bill->status !== 'paid') {
                return redirect()->back()->with('error', 'Receipt can only be sent for paid bills.');
            }

            $receiptNumber = 'RCP-' . str_pad($bill->id, 6, '0', STR_PAD_LEFT);
            $invoiceNumber = 'INV-' . str_pad($bill->id, 6, '0', STR_PAD_LEFT);

            $paymentDate = $bill->paid_date
                ? $bill->paid_date->format('F d, Y h:i A')
                : ($bill->updated_at ? $bill->updated_at->format('F d, Y h:i A') : now()->format('F d, Y h:i A'));

            $receiptData = [
                'receipt_number' => $receiptNumber,
                'invoice_number' => $invoiceNumber,
                'payment_date' => $paymentDate,
                'tenant' => [
                    'name' => $bill->tenant->name,
                    'email' => $bill->tenant->email,
                    'contact' => $bill->tenant->contact_number ?? '',
                    'apartment' => $bill->rent && $bill->rent->apartment
                        ? $bill->rent->apartment->apartment_number
                        : 'N/A'
                ],
                'items' => $this->getInvoiceItems($bill),
                'subtotal' => $bill->amount,
                'tax' => 0,
                'total' => $bill->amount,
                'amount_paid' => $bill->amount,
                'payment_method' => $bill->payment_method ?? 'Cash',
                'received_by' => auth()->user()->name,
            ];


            $pdf = app('dompdf.wrapper');
            $pdf->loadView('staff.billing.receipt-pdf', compact('receiptData'));

            // Save temporarily
            $pdfPath = storage_path('app/temp/' . $receiptNumber . '.pdf');
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }
            $pdf->save($pdfPath);

            Mail::to($bill->tenant->email)->send(new ReceiptMail($receiptData, $pdfPath));


            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }

            return redirect()->back()->with('success', 'Receipt sent to ' . $bill->tenant->email);

        } catch (\Exception $e) {
            \Log::error('Failed to send receipt email', [
                'bill_id' => $billId,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Failed to send receipt: ' . $e->getMessage());
        }
    }

    public function markAsPaid($id, Request $request)
    {
        $bill = Billing::findOrFail($id);

        $paymentMethod = $request->input('payment_method', 'cash');
        $staffNotes = $request->input('staff_notes', '');
        $sendReceipt = $request->input('send_receipt', false);

        $bill->update([
            'status' => 'paid',
            'paid_date' => now(),
            'payment_method' => $paymentMethod,
            'staff_notes' => $staffNotes ? $staffNotes : 'Marked as paid by staff: ' . auth()->user()->name,
        ]);

        $linkedOrder = Order::where('billing_id', $bill->id)->first();
        if ($linkedOrder) {
            $linkedOrder->update(['status' => 'paid']);
        }


        if ($sendReceipt) {
            try {
                $this->sendReceiptEmail($bill);
                $successMessage = 'Bill marked as paid and receipt sent to ' . $bill->tenant->email;
            } catch (\Exception $e) {
                \Log::error('Failed to send receipt email', [
                    'bill_id' => $id,
                    'error' => $e->getMessage()
                ]);
                $successMessage = 'Bill marked as paid but failed to send receipt email.';
            }
        } else {
            $successMessage = $staffNotes && str_contains($staffNotes, 'QR Payment')
                ? 'QR payment processed successfully for ' . $bill->tenant->name
                : 'Bill marked as paid successfully.';
        }

        return redirect()->route('staff.billing.index')->with('success', $successMessage);
    }


    public function sendReceiptEmail($billId)
    {
        try {
            $bill = Billing::with(['tenant', 'rent.apartment', 'billingItems'])
                ->findOrFail($billId);

            if ($bill->status !== 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Receipt can only be sent for paid bills'
                ], 400);
            }

            $receiptData = json_decode($this->getReceiptData($billId)->content(), true);

            Mail::to($bill->tenant->email)->send(new ReceiptMail($receiptData));

            return response()->json([
                'success' => true,
                'message' => 'Receipt email sent to ' . $bill->tenant->email
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send receipt email', [
                'bill_id' => $billId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function viewTenants()
    {

        $tenants = User::where('role', 'tenant')
            ->with(['tenantRents.apartment'])
            ->orderBy('created_at', 'desc')
            ->get();


        \Log::info('Loaded tenants data:', [
            'total_count' => $tenants->count(),
            'active_count' => $tenants->where('is_active', 1)->count(),
            'inactive_count' => $tenants->where('is_active', 0)->count(),
            'tenant_details' => $tenants->map(function ($t) {
                return [
                    'id' => $t->id,
                    'name' => $t->name,
                    'is_active' => $t->is_active,
                    'is_active_raw' => $t->getRawOriginal('is_active')
                ];
            })
        ]);

        return view('staff.tenants.index', compact('tenants'));
    }
    public function viewTenant($id)
    {
        $tenant = User::tenants()->with('apartment')->findOrFail($id);

        $tenantData = [
            'rent_status' => $this->getTenantRentStatus($tenant),
            'last_payment' => $this->getLastPaymentDate($tenant),
            'pending_maintenance' => Maintenance::where('tenant_id', $tenant->id)
                ->where('status', '!=', 'completed')
                ->count(),
            'total_orders' => Order::where('tenant_id', $tenant->id)->count(),
        ];

        return view('staff.tenants.show', compact('tenant', 'tenantData'));
    }


    public function getTenantDetailsForQR($id)
    {
        try {
            \Log::info('QR Tenant lookup requested', ['tenant_id' => $id]);

            $tenant = User::where('role', 'tenant')->find($id);

            if (!$tenant) {
                \Log::warning('Tenant not found for QR lookup', ['tenant_id' => $id]);
                return response()->json(['error' => 'Tenant not found'], 404);
            }

            \Log::info('Tenant found for QR lookup', [
                'tenant_id' => $id,
                'tenant_name' => $tenant->name
            ]);

            return response()->json([
                'id' => $tenant->id,
                'name' => $tenant->name,
                'email' => $tenant->email,
                'contact_number' => $tenant->contact_number ?? ''
            ]);

        } catch (\Exception $e) {
            \Log::error('QR Tenant lookup failed', [
                'tenant_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Server error occurred',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function getEditData(Apartment $apartment)
    {
        return response()->json([
            'apartment' => [
                'id' => $apartment->id,
                'apartment_number' => $apartment->apartment_number,
                'apartment_type' => $apartment->apartment_type,
                'price' => $apartment->price,
                'floor_number' => $apartment->floor_number,
                'size_sqm' => $apartment->size_sqm,
                'status' => $apartment->status,
                'description' => $apartment->description,
                'amenities' => $apartment->amenities ?? []
            ]
        ]);
    }
    public function createTenants()
    {
        return view('staff.tenants.create');
    }


    public function storeTenant(Request $request)
    {
        if ($request->has('use_default_password')) {
            $plainPassword = 'password123';
        } else {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $plainPassword = $request->password;
        }


        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'contact_number' => 'required|nullable|string|max:11',
            'is_active' => 'required|boolean',
            'apartment_id' => 'nullable|exists:apartments,id',
            'monthly_rent' => 'nullable|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {

            $tenant = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($plainPassword),
                'role' => 'tenant',
                'contact_number' => $request->contact_number,
                'is_active' => $request->is_active,
            ]);

            $apartmentInfo = null;

            if ($request->filled('apartment_id') && $request->filled('monthly_rent')) {
                $apartment = Apartment::findOrFail($request->apartment_id);

                if ($apartment->status !== 'available') {
                    throw new \Exception('Selected apartment is not available.');
                }

                $rent = Rent::create([
                    'apartment_id' => $request->apartment_id,
                    'tenant_id' => $tenant->id,
                    'staff_id' => auth()->id(),
                    'monthly_rent' => $request->monthly_rent,
                    'security_deposit' => $request->security_deposit ?? 0,
                    'start_date' => $request->start_date ?? now(),
                    'status' => 'active',
                    'terms' => 'Standard lease agreement',
                ]);


                $apartment->update(['status' => 'occupied']);


                $apartmentInfo = [
                    'apartment_number' => $apartment->apartment_number,
                    'monthly_rent' => $request->monthly_rent,
                    'security_deposit' => $request->security_deposit ?? 0,
                    'start_date' => $request->start_date ?? now()->format('Y-m-d'),
                ];
            }


            try {
                Mail::to($tenant->email)->send(new \App\Mail\TenantWelcomeMail(
                    $tenant,
                    $plainPassword,
                    $apartmentInfo
                ));

                \Log::info('Welcome email sent successfully', [
                    'tenant_id' => $tenant->id,
                    'email' => $tenant->email
                ]);

                $successMessage = 'Tenant created successfully and welcome email sent to ' . $tenant->email;
            } catch (\Exception $e) {
                \Log::error('Failed to send welcome email', [
                    'tenant_id' => $tenant->id,
                    'email' => $tenant->email,
                    'error' => $e->getMessage()
                ]);

                $successMessage = 'Tenant created successfully, but failed to send welcome email. Please provide credentials manually.';
            }

            DB::commit();

            return redirect()->route('staff.tenants.index')->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Failed to create tenant', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create tenant: ' . $e->getMessage());
        }
    }

    public function editTenant(User $user)
    {
        if ($user->role !== 'tenant') {
            abort(404);
        }
        return view('staff.tenants.edit', compact('user'));
    }

    public function updateTenant(Request $request, User $user)
    {
        if ($user->role !== 'tenant') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'contact_number' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('staff.tenants.index')->with('success', 'Tenant updated successfully.');
    }

    public function deleteTenant(User $user)
    {
        if ($user->role !== 'tenant') {
            abort(404);
        }

        $user->update(['is_active' => false]);

        return redirect()->route('staff.tenants.index')->with('success', 'Tenant deactivated successfully.');
    }

    public function activateTenant($id)
    {
        try {
            $tenant = User::where('role', 'tenant')->findOrFail($id);

            $tenant->update(['is_active' => 1]);

            return redirect()->route('staff.tenants.index')
                ->with('success', "Tenant '{$tenant->name}' has been activated successfully.");

        } catch (\Exception $e) {
            \Log::error('Error activating tenant', [
                'tenant_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('staff.tenants.index')
                ->with('error', 'Failed to activate tenant. Please try again.');
        }
    }


    public function deactivateTenant($id)
    {
        try {
            $tenant = User::where('role', 'tenant')->findOrFail($id);


            $activeRentCount = DB::table('rent')
                ->where('tenant_id', $id)
                ->where('status', 'active')
                ->where(function ($query) {
                    $query->whereNull('end_date')
                        ->orWhere('end_date', '>', now());
                })
                ->count();

            if ($activeRentCount > 0) {
                return redirect()->route('staff.tenants.index')
                    ->with('error', 'Cannot deactivate tenant with active apartment rental. Please terminate the rent agreement first.');
            }


            try {
                $unpaidBillsCount = DB::table('billings')
                    ->where('tenant_id', $id)
                    ->whereIn('status', ['pending', 'overdue'])
                    ->count();

                if ($unpaidBillsCount > 0) {
                    return redirect()->route('staff.tenants.index')
                        ->with('warning', "Tenant has {$unpaidBillsCount} unpaid bill(s). Consider resolving these before deactivation.");
                }
            } catch (\Exception $e) {

                \Log::info('Billing check skipped - table may not exist');
            }

            $tenant->update(['is_active' => 0]);

            return redirect()->route('staff.tenants.index')
                ->with('success', "Tenant '{$tenant->name}' has been deactivated successfully.");

        } catch (\Exception $e) {
            \Log::error('Error deactivating tenant', [
                'tenant_id' => $id,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return redirect()->route('staff.tenants.index')
                ->with('error', 'Failed to deactivate tenant. Error: ' . $e->getMessage());
        }
    }

    public function toggleTenantStatus($id)
    {
        try {
            $tenant = User::where('role', 'tenant')->findOrFail($id);

            if ($tenant->is_active) {
                return $this->deactivateTenant($id);
            } else {
                return $this->activateTenant($id);
            }

        } catch (\Exception $e) {
            return redirect()->route('staff.tenants.index')
                ->with('error', 'Tenant not found.');
        }
    }

    public function advancedTenantSearch()
    {

        $tenants = User::where('role', 'tenant')
            ->with(['tenantRents.apartment', 'tenantBills'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($tenant) {

                $outstandingBalance = $tenant->tenantBills()
                    ->whereIn('status', ['pending', 'overdue'])
                    ->sum('amount');

                $tenant->outstanding_balance = $outstandingBalance;
                $tenant->has_overdue = $tenant->tenantBills()
                    ->where('status', 'overdue')
                    ->exists();

                return $tenant;
            });

        return view('staff.tenants.index', compact('tenants'));
    }

    public function getTenantRecord($id)
    {
        try {
            $tenant = User::where('role', 'tenant')
                ->with([
                    'tenantRents.apartment',
                    'tenantBills',
                    'tenantOrders',
                    'tenantMaintenanceRequests'
                ])
                ->findOrFail($id);


            $outstandingBalance = $tenant->tenantBills()
                ->whereIn('status', ['pending', 'overdue'])
                ->sum('amount');

            $totalPaid = $tenant->tenantBills()
                ->where('status', 'paid')
                ->sum('amount');

            $lastPayment = $tenant->tenantBills()
                ->where('status', 'paid')
                ->latest('paid_date')
                ->first();


            $recentActivity = collect();

            $tenant->tenantMaintenanceRequests()->latest()->take(3)->get()->each(function ($request) use ($recentActivity) {
                $recentActivity->push([
                    'type' => 'maintenance',
                    'description' => 'Maintenance Request: ' . Str::limit($request->issue_description, 50),
                    'date' => $request->created_at,
                    'status' => $request->status
                ]);
            });

            $tenant->tenantOrders()->latest()->take(3)->get()->each(function ($order) use ($recentActivity) {
                $recentActivity->push([
                    'type' => 'order',
                    'description' => 'Cafe Order: ₱' . number_format($order->amount, 2),
                    'date' => $order->created_at,
                    'status' => $order->status
                ]);
            });

            $tenant->tenantBills()->where('status', 'paid')->latest('paid_date')->take(3)->get()->each(function ($bill) use ($recentActivity) {
                $recentActivity->push([
                    'type' => 'payment',
                    'description' => 'Payment: ₱' . number_format($bill->amount, 2),
                    'date' => $bill->paid_date,
                    'status' => 'completed'
                ]);
            });

            $recentActivity = $recentActivity->sortByDesc('date')->take(10);

            return response()->json([
                'tenant' => $tenant,
                'financial_summary' => [
                    'outstanding_balance' => $outstandingBalance,
                    'total_paid' => $totalPaid,
                    'last_payment_date' => $lastPayment ? $lastPayment->paid_date->format('M d, Y') : 'Never',
                    'payment_reliability' => $this->calculatePaymentReliability($tenant),
                ],
                'recent_activity' => $recentActivity,
                'current_rent' => $tenant->currentRent
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

    }
    public function getTenantBalance($id)
    {
        try {
            $tenant = User::where('role', 'tenant')->findOrFail($id);

            $outstandingBills = $tenant->tenantBills()
                ->whereIn('status', ['pending', 'overdue'])
                ->orderBy('due_date', 'asc')
                ->get()
                ->map(function ($bill) {
                    return [
                        'id' => $bill->id,
                        'description' => $bill->description,
                        'amount' => $bill->amount,
                        'due_date' => $bill->due_date->format('M d, Y'),
                        'status' => $bill->status,
                        'is_overdue' => $bill->due_date < now()
                    ];
                });

            $paymentHistory = $tenant->tenantBills()
                ->where('status', 'paid')
                ->orderBy('paid_date', 'desc')
                ->take(10)
                ->get()
                ->map(function ($bill) {
                    return [
                        'description' => $bill->description,
                        'amount' => $bill->amount,
                        'paid_date' => $bill->paid_date->format('M d, Y'),
                        'payment_method' => $bill->payment_method ?? 'Cash'
                    ];
                });

            $totalOutstanding = $outstandingBills->sum('amount');
            $totalPaid = $tenant->tenantBills()->where('status', 'paid')->sum('amount');

            return response()->json([
                'total_outstanding' => $totalOutstanding,
                'total_paid_lifetime' => $totalPaid,
                'outstanding_bills' => $outstandingBills,
                'payment_history' => $paymentHistory,
                'payment_reliability' => $this->calculatePaymentReliability($tenant)
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }
    }

    public function generateTenantReport($id)
    {
        try {
            $tenant = User::where('role', 'tenant')
                ->with([
                    'tenantRents.apartment',
                    'tenantBills',
                    'tenantOrders.orderItems.product',
                    'tenantMaintenanceRequests'
                ])
                ->findOrFail($id);

            $reportData = [
                'tenant' => $tenant,
                'generated_at' => now()->format('F j, Y g:i A'),
                'generated_by' => auth()->user()->name,


                'financial_summary' => [
                    'total_paid' => $tenant->tenantBills()->where('status', 'paid')->sum('amount'),
                    'outstanding_balance' => $tenant->tenantBills()->whereIn('status', ['pending', 'overdue'])->sum('amount'),
                    'average_monthly_payment' => $this->calculateAverageMonthlyPayment($tenant),
                    'payment_reliability' => $this->calculatePaymentReliability($tenant),
                    'late_payment_count' => $tenant->tenantBills()->where('status', 'overdue')->count(),
                ],


                'activity_summary' => [
                    'total_maintenance_requests' => $tenant->tenantMaintenanceRequests()->count(),
                    'resolved_maintenance' => $tenant->tenantMaintenanceRequests()->where('status', 'completed')->count(),
                    'total_cafe_orders' => $tenant->tenantOrders()->count(),
                    'cafe_spending' => $tenant->tenantOrders()->sum('amount'),
                    'lease_violations' => 0,
                ],


                'rental_info' => $tenant->currentRent ? [
                    'apartment_number' => $tenant->currentRent->apartment->apartment_number,
                    'monthly_rent' => $tenant->currentRent->monthly_rent,
                    'lease_start' => $tenant->currentRent->start_date->format('M d, Y'),
                    'lease_end' => $tenant->currentRent->end_date ? $tenant->currentRent->end_date->format('M d, Y') : 'Month-to-month',
                    'security_deposit' => $tenant->currentRent->security_deposit
                ] : null,
            ];


            $pdf = app('dompdf.wrapper');
            $pdf->loadView('staff.tenants.report-pdf', compact('reportData'));

            $filename = 'tenant_report_' . $tenant->id . '_' . now()->format('Y_m_d') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate report: ' . $e->getMessage());
        }
    }

    public function exportTenants(Request $request)
    {
        try {
            $query = User::where('role', 'tenant')
                ->with(['tenantRents.apartment', 'tenantBills']);

            if ($request->has('status') && $request->status !== '') {
                $isActive = $request->status === 'active';
                $query->where('is_active', $isActive);
            }

            if ($request->has('has_apartment')) {
                if ($request->has_apartment === 'yes') {
                    $query->whereHas('tenantRents', function ($q) {
                        $q->where('status', 'active');
                    });
                } else {
                    $query->whereDoesntHave('tenantRents', function ($q) {
                        $q->where('status', 'active');
                    });
                }
            }

            $tenants = $query->get();


            $csvData = [];
            $csvData[] = [
                'ID',
                'Name',
                'Email',
                'Phone',
                'Status',
                'Apartment',
                'Monthly Rent',
                'Outstanding Balance',
                'Total Paid',
                'Join Date',
                'Last Activity'
            ];

            foreach ($tenants as $tenant) {
                $outstandingBalance = $tenant->tenantBills()
                    ->whereIn('status', ['pending', 'overdue'])
                    ->sum('amount');

                $totalPaid = $tenant->tenantBills()
                    ->where('status', 'paid')
                    ->sum('amount');

                $csvData[] = [
                    $tenant->id,
                    $tenant->name,
                    $tenant->email,
                    $tenant->contact_number ?? '',
                    $tenant->is_active ? 'Active' : 'Inactive',
                    $tenant->currentRent ? $tenant->currentRent->apartment->apartment_number : 'None',
                    $tenant->currentRent ? $tenant->currentRent->monthly_rent : 0,
                    $outstandingBalance,
                    $totalPaid,
                    $tenant->created_at->format('Y-m-d'),
                    $tenant->updated_at->format('Y-m-d H:i')
                ];
            }


            $filename = 'tenants_export_' . now()->format('Y_m_d_His') . '.csv';

            $handle = fopen('php://output', 'w');
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            foreach ($csvData as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
            exit;

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }

    public function bulkUpdateTenants(Request $request)
    {
        try {
            $tenantIds = $request->input('tenant_ids', []);
            $action = $request->input('action');

            if (empty($tenantIds)) {
                return response()->json(['error' => 'No tenants selected'], 400);
            }

            $count = 0;
            $tenants = User::whereIn('id', $tenantIds)->where('role', 'tenant');

            switch ($action) {
                case 'activate':
                    $count = $tenants->update(['is_active' => true]);
                    break;

                case 'deactivate':
                    $count = $tenants->update(['is_active' => false]);
                    break;

                case 'send_reminder':

                    foreach ($tenants->get() as $tenant) {

                        $count++;
                    }
                    break;

                case 'mark_reviewed':

                    $count = $tenants->update(['last_reviewed_at' => now()]);
                    break;

                default:
                    return response()->json(['error' => 'Invalid action'], 400);
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully processed {$count} tenant(s)",
                'action' => $action
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Bulk update failed: ' . $e->getMessage()], 500);
        }
    }



    private function calculatePaymentReliability($tenant)
    {
        $totalBills = $tenant->tenantBills()->count();
        if ($totalBills === 0)
            return 100;

        $paidOnTime = $tenant->tenantBills()
            ->where('status', 'paid')
            ->whereColumn('paid_date', '<=', 'due_date')
            ->count();

        return round(($paidOnTime / $totalBills) * 100, 1);
    }

    private function calculateAverageMonthlyPayment($tenant)
    {
        $payments = $tenant->tenantBills()
            ->where('status', 'paid')
            ->where('created_at', '>=', now()->subMonths(12))
            ->get();

        if ($payments->count() === 0)
            return 0;

        return round($payments->avg('amount'), 2);
    }
    public function viewReports(Request $request)
    {
        $period = $request->get('period', 'week');
        $apartmentStats = $this->getActiveApartmentStats();
        $apartmentTrend = $this->getApartmentActivityTrend();

        $reportData = [
            'completed_tasks' => Maintenance::where('status', 'completed')
                ->whereDate('completed_at', today())
                ->count(),
            'pending_tasks' => Maintenance::where('status', 'pending')->count(),
            'revenue_today' => Order::whereDate('created_at', today())
                ->where('status', 'paid')
                ->sum('amount'),
            'maintenance_completed' => Maintenance::where('status', 'completed')
                ->whereMonth('completed_at', now()->month)
                ->count(),
            'active_tenants' => User::where('role', 'tenant')
                ->where('is_active', true)
                ->count(),
            'total_tenants' => User::where('role', 'tenant')->count(),
            'active_apartments' => $apartmentStats['active_apartments'],
            'total_apartments' => $apartmentStats['total_apartments'],
            'occupancy_rate' => $apartmentStats['occupancy_rate'],
        ];

        $chartData = [
            'tasks' => $this->getTaskChartData(),
            'revenue' => $this->getRevenueChartData(),
            'tenants' => $this->getTenantChartData(),
            'apartments' => $apartmentTrend,
        ];

        return view('staff.reports.index', compact('reportData', 'chartData', 'period', 'apartmentStats'));
    }
    private function getTenantChartData()
    {
        $data = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M j');

            $activeTenants = User::where('role', 'tenant')
                ->where('is_active', true)
                ->whereDate('created_at', '<=', $date)
                ->count();

            $data[] = $activeTenants;
        }

        return [
            'labels' => $labels,
            'values' => $data
        ];
    }

    private function calculateTenantRetentionRate()
    {
        try {
            $startOfMonth = now()->startOfMonth();
            $endOfMonth = now()->endOfMonth();

            $tenantsStartOfMonth = User::where('role', 'tenant')
                ->where('created_at', '<', $startOfMonth)
                ->where('is_active', true)
                ->count();


            $tenantsStillActive = User::where('role', 'tenant')
                ->where('created_at', '<', $startOfMonth)
                ->where('is_active', true)
                ->whereDoesntHave('tenantRents', function ($query) use ($endOfMonth) {
                    $query->where('status', 'terminated')
                        ->where('end_date', '<=', $endOfMonth);
                })
                ->count();

            return $tenantsStartOfMonth > 0 ? round(($tenantsStillActive / $tenantsStartOfMonth) * 100, 1) : 100;
        } catch (\Exception $e) {
            \Log::error('Error calculating tenant retention rate: ' . $e->getMessage());
            return 95.0;
        }
    }
    private function calculateTenantPaymentRate()
    {
        try {
            $totalBills = Billing::whereMonth('created_at', now()->month)->count();
            $paidBills = Billing::whereMonth('created_at', now()->month)
                ->where('status', 'paid')
                ->count();

            return $totalBills > 0 ? round(($paidBills / $totalBills) * 100, 1) : 100;
        } catch (\Exception $e) {
            \Log::error('Error calculating tenant payment rate: ' . $e->getMessage());
            return 85.0;
        }
    }

    private function calculateTenantSatisfaction()
    {

        try {
            $totalMaintenanceRequests = Maintenance::whereMonth('created_at', now()->month)->count();
            $urgentRequests = Maintenance::whereMonth('created_at', now()->month)
                ->where('priority', 'urgent')
                ->count();
            $satisfactionScore = $totalMaintenanceRequests > 0
                ? 5 - (($urgentRequests / $totalMaintenanceRequests) * 2)
                : 4.5;

            return max(1, min(5, round($satisfactionScore, 1)));
        } catch (\Exception $e) {
            \Log::error('Error calculating tenant satisfaction: ' . $e->getMessage());
            return 4.2;
        }
    }

    private function getTenantsWithOverdueBills()
    {
        try {
            return User::where('role', 'tenant')
                ->whereHas('tenantBills', function ($query) {
                    $query->where('status', 'overdue')
                        ->orWhere(function ($q) {
                            $q->where('status', 'pending')
                                ->where('due_date', '<', now());
                        });
                })
                ->count();
        } catch (\Exception $e) {
            \Log::error('Error getting tenants with overdue bills: ' . $e->getMessage());
            return 0;
        }
    }


    private function calculateAverageTenantStay()
    {
        try {
            $averageStay = Rent::where('status', 'active')
                ->selectRaw('AVG(DATEDIFF(CURDATE(), start_date)) as avg_days')
                ->first();

            return $averageStay && $averageStay->avg_days
                ? round($averageStay->avg_days / 30.44, 1)
                : 0;
        } catch (\Exception $e) {
            \Log::error('Error calculating average tenant stay: ' . $e->getMessage());
            return 0;
        }
    }

    private function calculateTenantTurnoverRate()
    {
        try {
            $totalApartments = Apartment::count();
            $terminatedRents = Rent::where('status', 'terminated')
                ->whereMonth('end_date', now()->month)
                ->count();

            return $totalApartments > 0 ? round(($terminatedRents / $totalApartments) * 100, 1) : 0;
        } catch (\Exception $e) {
            \Log::error('Error calculating tenant turnover rate: ' . $e->getMessage());
            return 0;
        }
    }

    private function getTenantActivities()
    {
        try {
            $activities = collect();
            $newTenants = User::where('role', 'tenant')
                ->where('created_at', '>=', now()->subDays(7))
                ->get()
                ->map(function ($tenant) {
                    return [
                        'type' => 'new_tenant',
                        'description' => "New tenant registered: {$tenant->name}",
                        'details' => $tenant->email,
                        'date' => $tenant->created_at,
                        'tenant' => $tenant->name
                    ];
                });

            $recentRents = Rent::where('status', 'active')
                ->where('start_date', '>=', now()->subDays(7))
                ->with('tenant', 'apartment')
                ->get()
                ->map(function ($rent) {
                    return [
                        'type' => 'rent_assignment',
                        'description' => "Assigned {$rent->tenant->name} to Apt {$rent->apartment->apartment_number}",
                        'details' => "Monthly rent: ₱" . number_format($rent->monthly_rent, 2),
                        'date' => $rent->start_date,
                        'tenant' => $rent->tenant->name
                    ];
                });

            return $activities->merge($newTenants)
                ->merge($recentRents)
                ->sortByDesc('date')
                ->take(10)
                ->values();

        } catch (\Exception $e) {
            \Log::error('Error getting tenant activities: ' . $e->getMessage());
            return collect();
        }
    }

    private function getTaskChartData()
    {
        $data = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M j');

            $completedTasks = Maintenance::where('status', 'completed')
                ->whereDate('completed_at', $date)
                ->count();

            $data[] = $completedTasks;
        }

        return [
            'labels' => $labels,
            'values' => $data
        ];
    }

    private function getRevenueChartData()
    {
        $data = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M j');

            $dailyRevenue = Order::whereDate('created_at', $date)
                ->where('status', 'paid')
                ->sum('amount');

            $data[] = (float) $dailyRevenue;
        }

        return [
            'labels' => $labels,
            'values' => $data
        ];
    }


    private function getPopularMenuItems()
    {
        try {
            return OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                ->whereHas('order', function ($query) {
                    $query->whereMonth('created_at', now()->month);
                })
                ->groupBy('product_id')
                ->orderBy('total_quantity', 'desc')
                ->limit(5)
                ->with('product')
                ->get()
                ->map(function ($item) {
                    return [
                        'name' => $item->product->product_name ?? 'Unknown Item',
                        'quantity' => $item->total_quantity,
                        'revenue' => ($item->total_quantity * ($item->product->price ?? 0)),
                        'avg_price' => $item->product->price ?? 0
                    ];
                });
        } catch (\Exception $e) {
            \Log::error('Error getting popular items: ' . $e->getMessage());
            return [];
        }
    }
    private function getMaintenanceByCategory()
    {
        try {
            return Maintenance::select('category', DB::raw('COUNT(*) as count'))
                ->whereMonth('created_at', now()->month)
                ->groupBy('category')
                ->get()
                ->map(function ($item) {
                    return [
                        'category' => ucfirst($item->category ?? 'Unknown'),
                        'count' => $item->count
                    ];
                });
        } catch (\Exception $e) {
            \Log::error('Error getting maintenance by category: ' . $e->getMessage());
            return [];
        }
    }
    private function getMaintenanceByStatus()
    {
        try {
            return Maintenance::select('status', DB::raw('COUNT(*) as count'))
                ->whereMonth('created_at', now()->month)
                ->groupBy('status')
                ->get()
                ->map(function ($item) {
                    return [
                        'status' => ucfirst($item->status),
                        'count' => $item->count
                    ];
                });
        } catch (\Exception $e) {
            \Log::error('Error getting maintenance by status: ' . $e->getMessage());
            return [];
        }
    }
    private function calculateAverageResolutionTime()
    {
        try {
            $avgResolution = Maintenance::where('status', 'completed')
                ->whereNotNull('completed_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, completed_at)) as avg_hours')
                ->first();

            return $avgResolution ? round($avgResolution->avg_hours, 1) : 0;
        } catch (\Exception $e) {
            \Log::error('Error calculating resolution time: ' . $e->getMessage());
            return 0;
        }
    }

    private function calculateTaskCompletionRate()
    {
        try {
            $totalTasks = Maintenance::count();
            $completedTasks = Maintenance::where('status', 'completed')->count();

            return $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        } catch (\Exception $e) {
            \Log::error('Error calculating completion rate: ' . $e->getMessage());
            return 0;
        }
    }

    private function calculateAverageResponseTime()
    {
        try {
            $avgResponseTime = Maintenance::whereNotNull('staff_id')
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours')
                ->first();

            return $avgResponseTime ? round($avgResponseTime->avg_hours, 1) : 0;
        } catch (\Exception $e) {
            \Log::error('Error calculating response time: ' . $e->getMessage());
            return 0;
        }
    }



    private function calculateOccupancyRate()
    {
        $totalApartments = Apartment::count();
        $occupiedApartments = Apartment::where('status', 'occupied')->count();

        return $totalApartments > 0 ? round(($occupiedApartments / $totalApartments) * 100, 1) : 0;
    }


    private function getRecentActivities()
    {
        $activities = collect();
        $maintenanceActivities = Maintenance::where('status', 'completed')
            ->where('completed_at', '>=', now()->subDays(7))
            ->with('apartment', 'tenant')
            ->get()
            ->map(function ($maintenance) {
                return [
                    'type' => 'maintenance',
                    'description' => "Completed maintenance in Apt {$maintenance->apartment->apartment_number}",
                    'details' => $maintenance->issue_description,
                    'date' => $maintenance->completed_at,
                    'staff' => auth()->user()->name
                ];
            });


        $orderActivities = Order::where('created_at', '>=', now()->subDays(7))
            ->with('tenant')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($order) {
                return [
                    'type' => 'order',
                    'description' => "Processed cafe order for {$order->tenant->name}",
                    'details' => "₱" . number_format($order->amount, 2),
                    'date' => $order->created_at,
                    'staff' => auth()->user()->name
                ];
            });

        return $activities->merge($maintenanceActivities)
            ->merge($orderActivities)
            ->sortByDesc('date')
            ->take(20)
            ->values();
    }

    private function getTenantRentStatus($tenant)
    {
        $latestRentBill = Billing::where('tenant_id', $tenant->id)
            ->where('billing_type', 'rent')
            ->latest()
            ->first();

        if (!$latestRentBill)
            return 'no_bills';
        if ($latestRentBill->status === 'paid')
            return 'current';
        if ($latestRentBill->due_date < now())
            return 'overdue';

        return 'pending';
    }

    private function getLastPaymentDate($tenant)
    {
        $lastPayment = Billing::where('tenant_id', $tenant->id)
            ->where('status', 'paid')
            ->latest('paid_date')
            ->first();

        return $lastPayment ? $lastPayment->paid_date->format('Y-m-d') : null;
    }
    public function manageApartments()
    {

        $apartments = Apartment::with(['currentRent.tenant'])->get();
        $totalApartments = $apartments->count();
        $availableApartments = $apartments->where('status', 'available')->count();
        $occupiedApartments = $apartments->where('status', 'occupied')->count();
        $maintenanceApartments = $apartments->where('status', 'maintenance')->count();

        $apartmentsArray = $apartments->map(function ($apartment) {
            return [
                'id' => $apartment->id,
                'apartment_number' => $apartment->apartment_number,
                'apartment_type' => $apartment->apartment_type,
                'price' => $apartment->price,
                'floor_number' => $apartment->floor_number,
                'size_sqm' => $apartment->size_sqm,
                'status' => $apartment->status,
                'amenities' => $apartment->amenities ?? [],
                'description' => $apartment->description,
                'created_at' => $apartment->created_at,
                'current_tenant' => $apartment->currentRent ? [
                    'name' => $apartment->currentRent->tenant->name,
                    'email' => $apartment->currentRent->tenant->email,
                    'contact_number' => $apartment->currentRent->tenant->contact_number,
                    'monthly_rent' => $apartment->currentRent->monthly_rent,
                    'start_date' => $apartment->currentRent->start_date,
                    'end_date' => $apartment->currentRent->end_date,
                ] : null,
            ];
        })->toArray();

        return view('staff.apartments.index', compact(
            'totalApartments',
            'availableApartments',
            'occupiedApartments',
            'maintenanceApartments'
        ))->with('apartmentsData', $apartmentsArray);
    }

    public function getApartmentDetails($apartmentId)
    {
        $apartment = Apartment::findOrFail($apartmentId);

        $currentRent = Rent::where('apartment_id', $apartmentId)
            ->where('status', 'active')
            ->with('tenant')
            ->first();

        $response = [
            'apartment' => [
                'id' => $apartment->id,
                'apartment_number' => $apartment->apartment_number,
                'apartment_type' => $apartment->apartment_type,
                'price' => $apartment->price,
                'floor_number' => $apartment->floor_number,
                'size_sqm' => $apartment->size_sqm,
                'status' => $apartment->status,
                'amenities' => $apartment->amenities ?? [],
                'description' => $apartment->description,
                'created_at' => $apartment->created_at->toISOString(),
            ],
            'tenant' => null,
            'rent' => null
        ];

        if ($currentRent && $currentRent->tenant) {
            $response['tenant'] = [
                'id' => $currentRent->tenant->id,
                'name' => $currentRent->tenant->name,
                'email' => $currentRent->tenant->email,
                'contact_number' => $currentRent->tenant->contact_number,
            ];

            $response['rent'] = [
                'id' => $currentRent->id,
                'monthly_rent' => $currentRent->monthly_rent,
                'security_deposit' => $currentRent->security_deposit,
                'start_date' => $currentRent->start_date->toDateString(),
                'end_date' => $currentRent->end_date ? $currentRent->end_date->toDateString() : null,
                'status' => $currentRent->status,
                'terms' => $currentRent->terms,
            ];
        }

        return response()->json($response);
    }

    //apartment
    private function getActiveApartmentStats()
    {
        try {
            $totalApartments = Apartment::count();
            $activeApartments = Apartment::where('status', 'occupied')->count();
            $availableApartments = Apartment::where('status', 'available')->count();
            $maintenanceApartments = Apartment::where('status', 'maintenance')->count();

            $lastMonthOccupied = Rent::where('status', 'active')
                ->where('start_date', '<=', now()->subMonth())
                ->where(function ($query) {
                    $query->whereNull('end_date')
                        ->orWhere('end_date', '>', now()->subMonth());
                })
                ->distinct('apartment_id')
                ->count();

            $occupancyTrend = $lastMonthOccupied > 0
                ? round((($activeApartments - $lastMonthOccupied) / $lastMonthOccupied) * 100, 1)
                : ($activeApartments > 0 ? 100 : 0);


            $apartmentsByType = Apartment::select(
                'apartment_type',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "occupied" THEN 1 ELSE 0 END) as occupied')
            )
                ->groupBy('apartment_type')
                ->get()
                ->map(function ($item) {
                    return [
                        'type' => $item->apartment_type,
                        'total' => $item->total,
                        'occupied' => $item->occupied,
                        'occupancy_rate' => $item->total > 0 ? round(($item->occupied / $item->total) * 100, 1) : 0
                    ];
                });


            $avgRentByType = Rent::where('status', 'active')
                ->join('apartments', 'rents.apartment_id', '=', 'apartments.id')
                ->select('apartments.apartment_type', DB::raw('AVG(rents.monthly_rent) as avg_rent'))
                ->groupBy('apartments.apartment_type')
                ->get();

            return [
                'total_apartments' => $totalApartments,
                'active_apartments' => $activeApartments,
                'available_apartments' => $availableApartments,
                'maintenance_apartments' => $maintenanceApartments,
                'occupancy_rate' => $totalApartments > 0 ? round(($activeApartments / $totalApartments) * 100, 1) : 0,
                'occupancy_trend' => $occupancyTrend,
                'apartments_by_type' => $apartmentsByType,
                'avg_rent_by_type' => $avgRentByType,
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting active apartment stats: ' . $e->getMessage());
            return [
                'total_apartments' => Apartment::count(),
                'active_apartments' => Apartment::where('status', 'occupied')->count(),
                'available_apartments' => Apartment::where('status', 'available')->count(),
                'maintenance_apartments' => Apartment::where('status', 'maintenance')->count(),
                'occupancy_rate' => 0,
                'occupancy_trend' => 0,
                'apartments_by_type' => collect(),
                'avg_rent_by_type' => collect(),
            ];
        }
    }

    private function getApartmentActivityTrend()
    {
        try {
            $data = [];
            $labels = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->format('M j');

                $newlyOccupied = Rent::where('status', 'active')
                    ->whereDate('start_date', $date)
                    ->count();

                $newlyAvailable = Rent::where('status', 'terminated')
                    ->whereDate('end_date', $date)
                    ->count();

                $data[] = [
                    'date' => $date->format('Y-m-d'),
                    'newly_occupied' => $newlyOccupied,
                    'newly_available' => $newlyAvailable,
                    'net_change' => $newlyOccupied - $newlyAvailable
                ];
            }

            return [
                'labels' => $labels,
                'data' => $data
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting apartment activity trend: ' . $e->getMessage());
            return [
                'labels' => [],
                'data' => []
            ];
        }
    }

    private function getRecentApartmentActivities()
    {
        try {
            $activities = collect();
            $newAssignments = Rent::where('status', 'active')
                ->where('start_date', '>=', now()->subDays(7))
                ->join('users', 'rents.tenant_id', '=', 'users.id')
                ->join('apartments', 'rents.apartment_id', '=', 'apartments.id')
                ->select('rents.*', 'users.name as tenant_name', 'apartments.apartment_number')
                ->get()
                ->map(function ($rent) {
                    return [
                        'type' => 'apartment_assigned',
                        'description' => "Assigned {$rent->tenant_name} to Apartment {$rent->apartment_number}",
                        'details' => "Monthly rent: ₱" . number_format($rent->monthly_rent, 2),
                        'date' => $rent->start_date,
                        'apartment' => $rent->apartment_number,
                        'tenant' => $rent->tenant_name
                    ];
                });

            $newVacancies = Rent::where('status', 'terminated')
                ->where('end_date', '>=', now()->subDays(7))
                ->join('users', 'rents.tenant_id', '=', 'users.id')
                ->join('apartments', 'rents.apartment_id', '=', 'apartments.id')
                ->select('rents.*', 'users.name as tenant_name', 'apartments.apartment_number')
                ->get()
                ->map(function ($rent) {
                    return [
                        'type' => 'apartment_vacated',
                        'description' => "Apartment {$rent->apartment_number} vacated by {$rent->tenant_name}",
                        'details' => "Available for new tenant",
                        'date' => $rent->end_date,
                        'apartment' => $rent->apartment_number,
                        'tenant' => $rent->tenant_name
                    ];
                });
            $maintenanceUpdates = Apartment::where('status', 'maintenance')
                ->where('updated_at', '>=', now()->subDays(7))
                ->get()
                ->map(function ($apartment) {
                    return [
                        'type' => 'apartment_maintenance',
                        'description' => "Apartment {$apartment->apartment_number} put under maintenance",
                        'details' => "Status changed to maintenance",
                        'date' => $apartment->updated_at,
                        'apartment' => $apartment->apartment_number,
                        'tenant' => null
                    ];
                });

            return $activities->merge($newAssignments)
                ->merge($newVacancies)
                ->merge($maintenanceUpdates)
                ->sortByDesc('date')
                ->take(10)
                ->values();

        } catch (\Exception $e) {
            \Log::error('Error getting recent apartment activities: ' . $e->getMessage());
            return collect();
        }
    }


    private function getApartmentPerformanceMetrics()
    {
        try {

            $avgVacancyResult = DB::select("
            SELECT AVG(DATEDIFF(next_rent.start_date, prev_rent.end_date)) as avg_days
            FROM rents prev_rent
            JOIN rents next_rent ON prev_rent.apartment_id = next_rent.apartment_id
            WHERE prev_rent.status = 'terminated'
            AND next_rent.status = 'active'
            AND prev_rent.end_date < next_rent.start_date
            AND prev_rent.end_date >= ?
        ", [now()->subMonths(6)]);

            $avgVacancyDuration = isset($avgVacancyResult[0]) ? $avgVacancyResult[0]->avg_days ?? 0 : 0;

            $totalApartments = Apartment::count();
            $turnovers = Rent::where('status', 'terminated')
                ->where('end_date', '>=', now()->subMonths(3))
                ->count();
            $turnoverRate = $totalApartments > 0 ? round(($turnovers / $totalApartments) * 100, 1) : 0;
            $avgLeaseDuration = Rent::where('status', 'terminated')
                ->whereNotNull('end_date')
                ->selectRaw('AVG(DATEDIFF(end_date, start_date)) as avg_days')
                ->first();
            $avgLeaseMonths = $avgLeaseDuration && $avgLeaseDuration->avg_days
                ? round($avgLeaseDuration->avg_days / 30.44, 1)
                : 0;
            $revenuePerApartment = Rent::where('status', 'active')
                ->avg('monthly_rent') ?? 0;

            return [
                'avg_vacancy_duration' => round($avgVacancyDuration, 1),
                'turnover_rate' => $turnoverRate,
                'avg_lease_duration' => $avgLeaseMonths,
                'revenue_per_apartment' => $revenuePerApartment,
            ];
        } catch (\Exception $e) {
            \Log::error('Error calculating apartment performance metrics: ' . $e->getMessage());
            return [
                'avg_vacancy_duration' => 0,
                'turnover_rate' => 0,
                'avg_lease_duration' => 0,
                'revenue_per_apartment' => 0,
            ];
        }
    }


    public function downloadReports()
    {
        try {
            $apartmentStats = $this->getActiveApartmentStats();
            $apartmentPerformance = $this->getApartmentPerformanceMetrics();
            $apartmentActivities = $this->getRecentApartmentActivities();

            $reportData = [
                'generated_at' => now()->format('F j, Y g:i A'),
                'generated_by' => auth()->user()->name,
                'period' => request('period', 'week'),


                'today' => [
                    'completed_tasks' => Maintenance::where('status', 'completed')
                        ->whereDate('completed_at', today())
                        ->count(),
                    'pending_tasks' => Maintenance::where('status', 'pending')->count(),
                    'revenue' => Order::whereDate('created_at', today())
                        ->where('status', 'paid')
                        ->sum('amount') ?? 0,
                    'orders_processed' => Order::whereDate('created_at', today())->count(),
                    'active_tenants' => User::where('role', 'tenant')
                        ->where('is_active', true)
                        ->count(),
                    'active_apartments' => $apartmentStats['active_apartments'],
                ],


                'week' => [
                    'completed_maintenance' => Maintenance::where('status', 'completed')
                        ->whereBetween('completed_at', [now()->startOfWeek(), now()->endOfWeek()])
                        ->count(),
                    'total_orders' => Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                        ->count(),
                    'total_revenue' => Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                        ->where('status', 'paid')
                        ->sum('amount') ?? 0,
                    'bills_generated' => Billing::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                        ->count(),
                    'avg_order_value' => Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                        ->where('status', 'paid')
                        ->avg('amount') ?? 0,
                    'new_tenants' => User::where('role', 'tenant')
                        ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                        ->count(),
                    'new_apartment_assignments' => Rent::where('status', 'active')
                        ->whereBetween('start_date', [now()->startOfWeek(), now()->endOfWeek()])
                        ->count(),
                ],


                'month' => [
                    'maintenance_completed' => Maintenance::where('status', 'completed')
                        ->whereMonth('completed_at', now()->month)
                        ->count(),
                    'monthly_revenue' => Order::whereMonth('created_at', now()->month)
                        ->where('status', 'paid')
                        ->sum('amount') ?? 0,
                    'active_tenants' => User::where('role', 'tenant')
                        ->where('is_active', true)
                        ->count(),
                    'occupancy_rate' => $apartmentStats['occupancy_rate'],
                    'new_tenants_month' => User::where('role', 'tenant')
                        ->whereMonth('created_at', now()->month)
                        ->count(),
                    'tenant_retention_rate' => $this->calculateTenantRetentionRate(),
                ],

                'apartment_stats' => array_merge($apartmentStats, [
                    'occupancy_trend' => $apartmentStats['occupancy_trend'],
                    'apartments_by_type' => $apartmentStats['apartments_by_type'],
                    'avg_rent_by_type' => $apartmentStats['avg_rent_by_type'],
                    'performance_metrics' => $apartmentPerformance,
                ]),

                'billing_stats' => [
                    'total_bills_month' => Billing::whereMonth('created_at', now()->month)->count(),
                    'paid_bills_month' => Billing::whereMonth('created_at', now()->month)
                        ->where('status', 'paid')
                        ->count(),
                    'pending_bills' => Billing::where('status', 'pending')->count(),
                    'overdue_bills' => Billing::where('status', 'overdue')->count(),
                    'tenant_payment_rate' => $this->calculateTenantPaymentRate(),
                ],

                'performance' => [
                    'avg_resolution_time' => $this->calculateAverageResolutionTime(),
                    'task_completion_rate' => $this->calculateTaskCompletionRate(),
                    'response_time_hours' => $this->calculateAverageResponseTime(),
                    'tenant_satisfaction' => $this->calculateTenantSatisfaction(),
                ],

                'tenant_stats' => [
                    'active_tenants' => User::where('role', 'tenant')
                        ->where('is_active', true)
                        ->count(),
                    'inactive_tenants' => User::where('role', 'tenant')
                        ->where('is_active', false)
                        ->count(),
                    'total_tenants' => User::where('role', 'tenant')->count(),
                    'tenants_with_overdue_bills' => $this->getTenantsWithOverdueBills(),
                    'avg_tenant_stay' => $this->calculateAverageTenantStay(),
                    'tenant_turnover_rate' => $this->calculateTenantTurnoverRate(),
                ],

                'popular_items' => $this->getPopularMenuItems(),
                'maintenance_by_category' => $this->getMaintenanceByCategory(),
                'maintenance_by_status' => $this->getMaintenanceByStatus(),
                'recent_activities' => $this->getRecentActivities(),
                'tenant_activities' => $this->getTenantActivities(),
                'apartment_activities' => $apartmentActivities,
            ];


            $pdf = app('dompdf.wrapper');
            $pdf->loadView('staff.reports.pdf', compact('reportData'));
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'defaultFont' => 'Arial'
            ]);

            $filename = 'staff_report_' . now()->format('Y_m_d_His') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Error generating report', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Failed to generate report: ' . $e->getMessage());
        }
    }


    public function createApartment()
    {
        return view('staff.apartments.create');
    }
    public function storeApartment(Request $request)
    {
        $request->validate([
            'apartment_number' => 'required|string|max:10|unique:apartments,apartment_number',
            'apartment_type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'floor_number' => 'nullable|integer|min:1|max:50',
            'size_sqm' => 'nullable|numeric|min:1',
            'status' => 'required|string|in:available,occupied,maintenance',
            'amenities' => 'nullable|array',
            'description' => 'nullable|string|max:1000',
        ]);


        $apartment = Apartment::create([
            'apartment_number' => $request->apartment_number,
            'apartment_type' => $request->apartment_type,
            'price' => $request->price,
            'floor_number' => $request->floor_number,
            'size_sqm' => $request->size_sqm,
            'status' => $request->status,
            'amenities' => $request->amenities ?: [],
            'description' => $request->description,
        ]);

        return redirect()->route('staff.apartments.index')
            ->with('success', 'Apartment "' . $request->apartment_number . '" created successfully!');
    }
    public function editApartment($apartmentId)
    {

        $apartment = Apartment::findOrFail($apartmentId);

        return view('staff.apartments.edit', compact('apartment'));
    }

    public function updateApartment(Request $request, $apartmentId)
    {
        $apartment = Apartment::findOrFail($apartmentId);

        $request->validate([
            'apartment_number' => 'required|string|max:10|unique:apartments,apartment_number,' . $apartment->id,
            'apartment_type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'floor_number' => 'nullable|integer|min:1|max:50',
            'size_sqm' => 'nullable|numeric|min:1',
            'status' => 'required|string|in:available,occupied,maintenance',
            'amenities' => 'nullable|array',
            'description' => 'nullable|string|max:1000',
        ]);


        $apartment->update([
            'apartment_number' => $request->apartment_number,
            'apartment_type' => $request->apartment_type,
            'price' => $request->price,
            'floor_number' => $request->floor_number,
            'size_sqm' => $request->size_sqm,
            'status' => $request->status,
            'amenities' => $request->amenities ?: [],
            'description' => $request->description,
        ]);

        return redirect()->route('staff.apartments.index')
            ->with('success', 'Apartment "' . $request->apartment_number . '" updated successfully!');
    }

    public function deleteApartment($apartment)
    {

        $apartment = Apartment::findOrFail($apartment);
        $apartment->delete();


        return redirect()->route('staff.apartments.index')
            ->with('success', 'Apartment deleted successfully.');
    }
    public function assignTenant($apartmentId)
    {
        $apartment = Apartment::findOrFail($apartmentId);

        if ($apartment->status !== 'available') {
            return redirect()->route('staff.apartments.index')
                ->with('error', 'This apartment is not available for assignment.');
        }

        $availableTenants = User::where('role', 'tenant')
            ->where('is_active', true)
            ->whereDoesntHave('tenantrents', function ($query) {
                $query->where('status', 'active')
                    ->where(function ($q) {
                        $q->whereNull('end_date')
                            ->orWhere('end_date', '>', now());
                    });
            })
            ->get();

        return view('staff.apartments.assign', compact('apartment', 'availableTenants'));
    }
    public function getTenantDetails($apartmentId)
    {
        $apartment = Apartment::findOrFail($apartmentId);

        $currentRent = Rent::where('apartment_id', $apartmentId)
            ->where('status', 'active')
            ->with('tenant')
            ->first();

        if (!$currentRent || !$currentRent->tenant) {
            return response()->json([
                'tenant' => null,
                'apartment' => $apartment,
                'rent' => null
            ]);
        }

        return response()->json([
            'tenant' => [
                'name' => $currentRent->tenant->name,
                'email' => $currentRent->tenant->email,
                'contact_number' => $currentRent->tenant->contact_number,
            ],
            'apartment' => [
                'apartment_number' => $apartment->apartment_number,
                'apartment_type' => $apartment->apartment_type,
            ],
            'rent' => [
                'monthly_rent' => $currentRent->monthly_rent,
                'security_deposit' => $currentRent->security_deposit,
                'start_date' => $currentRent->start_date->toDateString(),
                'end_date' => $currentRent->end_date ? $currentRent->end_date->toDateString() : null,
                'status' => $currentRent->status,
            ]
        ]);
    }

    public function storeAssignment(Request $request, $apartmentId)
    {
        $apartment = Apartment::findOrFail($apartmentId);

        if ($apartment->status !== 'available') {
            return redirect()->route('staff.apartments.index')
                ->with('error', 'This apartment is no longer available for assignment.');
        }

        $request->validate([
            'tenant_id' => 'required|exists:users,id',
            'lease_start' => 'required|date|after_or_equal:today',
            'lease_duration' => 'required|integer|in:6,12,24,36',
            'monthly_rent' => 'required|numeric|min:0',
            'security_deposit' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $tenant = User::findOrFail($request->tenant_id);
        $existingRent = $tenant->tenantrents()->where('status', 'active')->first();

        if ($existingRent) {
            return redirect()->back()
                ->with('error', 'This tenant is already assigned to another apartment.')
                ->withInput();
        }


        $leaseStart = Carbon::parse($request->lease_start);
        $leaseDurationMonths = (int) $request->lease_duration;
        $leaseEnd = $leaseStart->copy()->addMonths($leaseDurationMonths);

        $rent = Rent::create([
            'apartment_id' => $apartment->id,
            'tenant_id' => $request->tenant_id,
            'staff_id' => auth()->id(),
            'monthly_rent' => $request->monthly_rent,
            'security_deposit' => $request->security_deposit,
            'start_date' => $request->lease_start,
            'end_date' => $leaseEnd,
            'status' => 'active',
            'terms' => $request->notes ?: 'Standard lease agreement for ' . $request->lease_duration . ' months.',
        ]);


        $apartment->update(['status' => 'occupied']);

        return redirect()->route('staff.apartments.index')
            ->with('success', "Tenant '{$tenant->name}' has been successfully assigned to apartment {$apartment->apartment_number}!");
    }
    public function posSystem()
    {
        $tenants = User::tenants()->active()->get();
        $menuItems = Product::where('is_active', true)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->product_name,
                    'price' => $product->price,
                    'category' => $product->category ?? 'general',
                ];
            })->toArray();

        return view('staff.pos.index', compact('tenants', 'menuItems'));
    }

    public function holdOrder(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $heldOrder = [
            'id' => uniqid(),
            'tenant_id' => $request->tenant_id,
            'items' => $request->items,
            'total_amount' => $request->total_amount,
            'notes' => $request->notes,
            'held_at' => now(),
            'staff_id' => auth()->id(),
        ];


        $heldOrders = session('held_orders', []);
        $heldOrders[] = $heldOrder;
        session(['held_orders' => $heldOrders]);

        return response()->json([
            'success' => true,
            'message' => 'Order held successfully',
            'order_id' => $heldOrder['id']
        ]);
    }

    public function getHeldOrders()
    {
        $heldOrders = session('held_orders', []);


        foreach ($heldOrders as &$order) {
            $tenant = User::find($order['tenant_id']);
            $order['tenant_name'] = $tenant ? $tenant->name : 'Unknown';
        }

        return response()->json($heldOrders);
    }

    public function recallOrder($id)
    {
        $heldOrders = session('held_orders', []);
        $order = null;

        foreach ($heldOrders as $index => $heldOrder) {
            if ($heldOrder['id'] === $id) {
                $order = $heldOrder;
                // Remove from held orders
                unset($heldOrders[$index]);
                session(['held_orders' => array_values($heldOrders)]);
                break;
            }
        }

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json([
            'success' => true,
            'order' => $order
        ]);
    }

    public function quickOrder(Request $request)
    {

        $quickOrders = [
            'coffee_combo' => [
                ['name' => 'Coffee', 'quantity' => 1],
                ['name' => 'Sandwich', 'quantity' => 1]
            ],
            'breakfast_combo' => [
                ['name' => 'Coffee', 'quantity' => 1],
                ['name' => 'Croissant', 'quantity' => 1],
                ['name' => 'Orange Juice', 'quantity' => 1]
            ]
        ];

        $comboType = $request->input('combo_type');

        if (!isset($quickOrders[$comboType])) {
            return response()->json(['error' => 'Invalid combo type'], 400);
        }

        $items = [];
        $totalAmount = 0;

        foreach ($quickOrders[$comboType] as $item) {
            $product = Product::where('product_name', $item['name'])->first();
            if ($product) {
                for ($i = 0; $i < $item['quantity']; $i++) {
                    $items[] = $product->product_name;
                }
                $totalAmount += $product->price * $item['quantity'];
            }
        }

        return response()->json([
            'items' => $items,
            'total_amount' => $totalAmount
        ]);
    }

    public function dailySummary()
    {
        $today = now()->startOfDay();

        $summary = [
            'total_orders' => Order::whereDate('created_at', $today)->count(),
            'total_revenue' => Order::whereDate('created_at', $today)->where('status', 'paid')->sum('amount'),
            'pending_orders' => Order::whereDate('created_at', $today)->where('status', 'unpaid')->count(),
            'popular_items' => OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                ->whereHas('order', function ($query) use ($today) {
                    $query->whereDate('created_at', $today);
                })
                ->groupBy('product_id')
                ->orderBy('total_quantity', 'desc')
                ->limit(5)
                ->with('product')
                ->get()
                ->map(function ($item) {
                    return [
                        'name' => $item->product->product_name,
                        'quantity' => $item->total_quantity
                    ];
                })
        ];

        return response()->json($summary);
    }

    public function sendInvoiceEmail($billId)
    {
        try {
            $bill = Billing::with(['tenant', 'rent.apartment', 'billingItems'])
                ->findOrFail($billId);

            $invoiceData = json_decode($this->getInvoiceData($billId)->content(), true);

            Mail::to($bill->tenant->email)->send(new InvoiceMail($invoiceData));

            return response()->json([
                'success' => true,
                'message' => 'Invoice email sent to ' . $bill->tenant->email
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send invoice email', [
                'bill_id' => $billId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function getInvoiceData($billId)
    {
        $bill = Billing::with(['tenant', 'rent.apartment', 'billingItems'])
            ->findOrFail($billId);

        $invoiceNumber = 'INV-' . str_pad($bill->id, 6, '0', STR_PAD_LEFT);


        $apartmentNumber = 'N/A';

        if ($bill->rent && $bill->rent->apartment) {
            $apartmentNumber = $bill->rent->apartment->apartment_number;
        } else {
            $activeRent = \App\Models\Rent::where('tenant_id', $bill->tenant_id)
                ->where('status', 'active')
                ->with('apartment')
                ->first();

            if ($activeRent && $activeRent->apartment) {
                $apartmentNumber = $activeRent->apartment->apartment_number;
            }
        }

        $invoiceData = [
            'invoice_number' => $invoiceNumber,
            'invoice_date' => $bill->created_at->format('F d, Y'),
            'due_date' => $bill->due_date->format('F d, Y'),
            'bill_id' => $bill->id,
            'tenant' => [
                'name' => $bill->tenant->name,
                'email' => $bill->tenant->email,
                'contact' => $bill->tenant->contact_number ?? '',
                'apartment' => $apartmentNumber
            ],
            'items' => $this->getInvoiceItems($bill),
            'subtotal' => $bill->amount,
            'tax' => 0,
            'total' => $bill->amount,
            'status' => $bill->status,
            'payment_terms' => 'Payment due within 7 days of invoice date',
            'notes' => $bill->description ?? '',
        ];

        return response()->json($invoiceData);
    }


    public function getReceiptData($billId)
    {
        $bill = Billing::with(['tenant', 'rent.apartment', 'billingItems'])
            ->findOrFail($billId);

        if ($bill->status !== 'paid') {
            return response()->json(['error' => 'Receipt only available for paid bills'], 400);
        }

        $receiptNumber = 'RCP-' . str_pad($bill->id, 6, '0', STR_PAD_LEFT);
        $invoiceNumber = 'INV-' . str_pad($bill->id, 6, '0', STR_PAD_LEFT);

        $paymentDate = $bill->paid_date
            ? $bill->paid_date->format('F d, Y h:i A')
            : ($bill->updated_at ? $bill->updated_at->format('F d, Y h:i A') : now()->format('F d, Y h:i A'));


        $apartmentNumber = 'N/A';

        if ($bill->rent && $bill->rent->apartment) {

            $apartmentNumber = $bill->rent->apartment->apartment_number;
        } else {

            $activeRent = \App\Models\Rent::where('tenant_id', $bill->tenant_id)
                ->where('status', 'active')
                ->with('apartment')
                ->first();

            if ($activeRent && $activeRent->apartment) {
                $apartmentNumber = $activeRent->apartment->apartment_number;
            }
        }

        $receiptData = [
            'receipt_number' => $receiptNumber,
            'invoice_number' => $invoiceNumber,
            'payment_date' => $paymentDate,
            'bill_id' => $bill->id,
            'tenant' => [
                'name' => $bill->tenant->name,
                'email' => $bill->tenant->email,
                'contact' => $bill->tenant->contact_number ?? '',
                'apartment' => $apartmentNumber,
                'apartment_number' => $apartmentNumber
            ],
            'items' => $this->getInvoiceItems($bill),
            'subtotal' => $bill->amount,
            'tax' => 0,
            'total' => $bill->amount,
            'amount_paid' => $bill->amount,
            'payment_method' => $bill->payment_method ?? 'Cash',
            'received_by' => auth()->user()->name,
            'notes' => 'Thank you for your payment!',
        ];

        return response()->json($receiptData);
    }

    private function getInvoiceItems($bill)
    {
        $items = [];

        if ($bill->billingItems && $bill->billingItems->count() > 0) {
            foreach ($bill->billingItems as $item) {
                $items[] = [
                    'description' => ucfirst(str_replace('_', ' ', $item->type)),
                    'details' => $item->description ?? '',
                    'quantity' => 1,
                    'unit_price' => $item->amount,
                    'total' => $item->amount
                ];
            }
        } else {
            $billingTypes = explode(',', $bill->billing_type);
            $description = count($billingTypes) > 1
                ? 'Consolidated Bill - ' . implode(', ', array_map('ucfirst', $billingTypes))
                : ucfirst(str_replace('_', ' ', $bill->billing_type));

            $items[] = [
                'description' => $description,
                'details' => $bill->description ?? '',
                'quantity' => 1,
                'unit_price' => $bill->amount,
                'total' => $bill->amount
            ];
        }

        return $items;
    }


    public function downloadInvoicePDF($billId)
    {
        $bill = Billing::with(['tenant', 'rent.apartment', 'billingItems'])
            ->findOrFail($billId);

        $invoiceNumber = 'INV-' . str_pad($bill->id, 6, '0', STR_PAD_LEFT);

        $invoiceData = [
            'invoice_number' => $invoiceNumber,
            'invoice_date' => $bill->created_at->format('F d, Y'),
            'due_date' => $bill->due_date->format('F d, Y'),
            'tenant' => [
                'name' => $bill->tenant->name,
                'email' => $bill->tenant->email,
                'contact' => $bill->tenant->contact_number ?? '',
                'apartment' => $bill->rent && $bill->rent->apartment
                    ? $bill->rent->apartment->apartment_number
                    : 'N/A'
            ],
            'items' => $this->getInvoiceItems($bill),
            'subtotal' => $bill->amount,
            'tax' => 0,
            'total' => $bill->amount,
            'status' => $bill->status,
            'payment_terms' => 'Payment due within 7 days of invoice date',
            'notes' => $bill->description ?? '',
        ];

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('staff.billing.invoice-pdf', compact('invoiceData'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download($invoiceNumber . '.pdf');
    }


    public function downloadReceiptPDF($billId)
    {
        $bill = Billing::with(['tenant', 'rent.apartment', 'billingItems'])
            ->findOrFail($billId);

        if ($bill->status !== 'paid') {
            return redirect()->back()->with('error', 'Receipt only available for paid bills.');
        }

        $receiptNumber = 'RCP-' . str_pad($bill->id, 6, '0', STR_PAD_LEFT);
        $invoiceNumber = 'INV-' . str_pad($bill->id, 6, '0', STR_PAD_LEFT);

        // Handle null paid_date
        $paymentDate = $bill->paid_date
            ? $bill->paid_date->format('F d, Y h:i A')
            : ($bill->updated_at ? $bill->updated_at->format('F d, Y h:i A') : now()->format('F d, Y h:i A'));

        $receiptData = [
            'receipt_number' => $receiptNumber,
            'invoice_number' => $invoiceNumber,
            'payment_date' => $paymentDate,
            'tenant' => [
                'name' => $bill->tenant->name,
                'email' => $bill->tenant->email,
                'contact' => $bill->tenant->contact_number ?? '',
                'apartment' => $bill->rent && $bill->rent->apartment
                    ? $bill->rent->apartment->apartment_number
                    : 'N/A'
            ],
            'items' => $this->getInvoiceItems($bill),
            'subtotal' => $bill->amount,
            'tax' => 0,
            'total' => $bill->amount,
            'amount_paid' => $bill->amount,
            'payment_method' => $bill->payment_method ?? 'Cash',
            'received_by' => auth()->user()->name,
        ];

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('staff.billing.receipt-pdf', compact('receiptData'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download($receiptNumber . '.pdf');
    }
}