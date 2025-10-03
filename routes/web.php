<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\TenantController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\StaffMiddleware;
use App\Http\Middleware\TenantMiddleware;
use App\Http\Models\User;
use App\Http\Controllers\Admin\AdminStaffController;
use App\Http\Controllers\Admin\AdminTenantController;
use App\Http\Controllers\Admin\AdminApartmentController;
use App\Http\Controllers\Admin\AdminBillingController;
use App\Http\Controllers\Admin\AdminReportController;

Route::get('/test-email', function () {
    try {
        Mail::raw('Test email from Laravel', function ($message) {
            $message->to('sherwin.aleonar2023@gmail.com')
                ->subject('Test Email');
        });
        return 'Email sent! Check your inbox.';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        switch ($user->role) {
            case 'owner':
                return redirect()->route('owner.dashboard');
            case 'staff':
                return redirect()->route('staff.dashboard');
            case 'tenant':
                return redirect()->route('tenant.dashboard');
            default:
                return redirect()->route('login');
        }
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();
    switch ($user->role) {
        case 'owner':
            return redirect()->route('owner.dashboard');
        case 'staff':
            return redirect()->route('staff.dashboard');
        case 'tenant':
            return redirect()->route('tenant.dashboard');
        default:
            return redirect()->route('login');
    }
})->name('dashboard')->middleware('auth');

// Owner
Route::middleware(['auth', AdminMiddleware::class])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');

    // Staff Management
    Route::get('/staff', [AdminStaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [AdminStaffController::class, 'createStaff'])->name('staff.create');
    Route::post('/staff', [AdminStaffController::class, 'storeStaff'])->name('staff.store');
    Route::get('/staff/{user}/edit', [AdminStaffController::class, 'editStaff'])->name('staff.edit');
    Route::put('/staff/{user}', [AdminStaffController::class, 'updateStaff'])->name('staff.update');
    Route::delete('/staff/{user}', [AdminStaffController::class, 'deleteStaff'])->name('staff.destroy');

    // Tenant Management
    Route::get('/tenants', [AdminTenantController::class, 'index'])->name('tenants.index');
    Route::get('/tenants/create', [AdminTenantController::class, 'createTenant'])->name('tenants.create');
    Route::post('/tenants', [AdminTenantController::class, 'storeTenant'])->name('tenants.store');
    Route::get('/tenants/{user}/edit', [AdminTenantController::class, 'editTenant'])->name('tenants.edit');
    Route::put('/tenants/{user}', [AdminTenantController::class, 'updateTenant'])->name('tenants.update');
    Route::delete('/tenants/{user}', [AdminTenantController::class, 'deleteTenant'])->name('tenants.destroy');
    Route::post('tenants/{user}/toggle-status', [AdminTenantController::class, 'toggleStatus'])
        ->name('tenants.toggle-status');


    // Apartment Management
    Route::get('/apartments', [AdminApartmentController::class, 'index'])->name('apartments.index');
    Route::get('/apartments/create', [AdminApartmentController::class, 'createApartment'])->name('apartments.create');
    Route::post('/apartments', [AdminApartmentController::class, 'storeApartment'])->name('apartments.store');
    Route::get('/apartments/{apartment}/edit', [AdminApartmentController::class, 'editApartment'])->name('apartments.edit');
    Route::put('/apartments/{apartment}', [AdminApartmentController::class, 'updateApartment'])->name('apartments.update');
    Route::delete('/apartments/{apartment}', [AdminApartmentController::class, 'deleteApartment'])->name('apartments.destroy');

    Route::get('/apartments/{apartment}/assign', [AdminApartmentController::class, 'assignTenant'])->name('apartments.assign');
    Route::post('/apartments/{apartment}/assign', [AdminApartmentController::class, 'storeAssignment'])->name('apartments.assign.store');
    Route::get('/apartments/{apartment}/tenant-details', [AdminApartmentController::class, 'getTenantDetails'])->name('apartments.tenant.details');
    Route::get('/apartments/{apartment}/details', [AdminApartmentController::class, 'getApartmentDetails'])->name('apartments.details');
    Route::get('/apartments/{apartment}/edit-data', [AdminApartmentController::class, 'getEditData'])->name('owner.apartments.edit-data');

    // Enhanced Billing Management
    Route::prefix('billing')->name('billing.')->group(function () {
        // Main billing management page
        Route::get('/', [AdminBillingController::class, 'manageBilling'])->name('index');

        // Create new bill
        Route::get('/create', [AdminBillingController::class, 'createBill'])->name('create');
        Route::post('/store', [AdminBillingController::class, 'storeBill'])->name('store');

        // View bill details
        Route::get('/{billing}', [AdminBillingController::class, 'showBill'])->name('show');

        // Edit bill
        Route::get('/{billing}/edit', [AdminBillingController::class, 'editBill'])->name('edit');
        Route::put('/{billing}', [AdminBillingController::class, 'updateBill'])->name('update');

        // Bill actions
        Route::patch('/{billing}/mark-paid', [AdminBillingController::class, 'markBillAsPaid'])->name('mark-paid');
        Route::patch('/{billing}/cancel', [AdminBillingController::class, 'cancelBill'])->name('cancel');

        // Bulk operations
        Route::post('/generate-monthly', [AdminBillingController::class, 'generateMonthlyRentBills'])->name('generate-monthly');
        Route::post('/send-reminders', [AdminBillingController::class, 'sendPaymentReminders'])->name('send-reminders');
        Route::post('/update-overdue', [AdminBillingController::class, 'updateOverdueBills'])->name('update-overdue');

        // Export and download
        Route::get('/export', [AdminBillingController::class, 'exportBills'])->name('export');
        Route::get('/{billing}/pdf', [AdminBillingController::class, 'downloadBillPDF'])->name('pdf');
        Route::get('/{billing}/invoice', [AdminBillingController::class, 'downloadInvoice'])->name('invoice');

        // API endpoints for AJAX
        Route::get('/stats/api', [AdminBillingController::class, 'getBillingStats'])->name('stats.api');
    });

    // Reports
    Route::get('/reports', [AdminReportController::class, 'viewReports'])->name('reports');
    Route::get('/reports/revenue', [AdminReportController::class, 'revenueReport'])->name('reports.revenue');
    Route::get('/reports/occupancy', [AdminReportController::class, 'occupancyReport'])->name('reports.occupancy');
    Route::get('/reports/download', [AdminReportController::class, 'downloadReports'])->name('reports.download');
});

// Staff Routes
Route::middleware(['auth', StaffMiddleware::class])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');
    Route::get('/reports/download', [StaffController::class, 'downloadReports'])->name('staff.reports.download');


    // Apartment Management
    Route::get('/apartments', [StaffController::class, 'manageApartments'])->name('apartments.index');
    Route::get('/apartments/create', [StaffController::class, 'createApartment'])->name('apartments.create');
    Route::post('/apartments', [StaffController::class, 'storeApartment'])->name('apartments.store');
    Route::get('/apartments/{apartment}/edit', [StaffController::class, 'editApartment'])->name('apartments.edit');
    Route::put('/apartments/{apartment}', [StaffController::class, 'updateApartment'])->name('apartments.update');
    Route::delete('/apartments/{apartment}', [StaffController::class, 'deleteApartment'])->name('apartments.destroy');

    Route::get('/apartments/{apartment}/assign', [StaffController::class, 'assignTenant'])->name('apartments.assign');
    Route::post('/apartments/{apartment}/assign', [StaffController::class, 'storeAssignment'])->name('apartments.assign.store');
    Route::get('/apartments/{apartment}/tenant-details', [StaffController::class, 'getTenantDetails'])->name('apartments.tenant.details');
    Route::get('/apartments/{apartment}/details', [StaffController::class, 'getApartmentDetails'])->name('apartments.details');
    Route::post('/apartments/{apartment}/assign', [StaffController::class, 'storeAssignment'])->name('apartments.assign.store');
    Route::get('/apartments/{apartment}/edit-data', [StaffController::class, 'getEditData'])->name('staff.apartments.edit-data');

    // Maintenance Management

    Route::get('/maintenance', [StaffController::class, 'manageMaintenance'])->name('maintenance.index');
    Route::get('/maintenance/create', [StaffController::class, 'createMaintenance'])->name('maintenance.create');
    Route::post('/maintenance', [StaffController::class, 'storeMaintenance'])->name('maintenance.store');
    Route::get('/maintenance/{maintenance}/edit', [StaffController::class, 'editMaintenance'])->name('maintenance.edit');
    Route::put('/maintenance/{maintenance}', [StaffController::class, 'updateMaintenance'])->name('maintenance.update');
    Route::delete('/maintenance/{maintenance}', [StaffController::class, 'deleteMaintenance'])->name('maintenance.destroy');
    Route::put('/maintenance/{maintenance}/complete', [StaffController::class, 'completeMaintenance'])->name('maintenance.complete');

    // Orders routes
    Route::get('/orders', [StaffController::class, 'manageOrders'])->name('orders.index');
    Route::get('/orders/create', [StaffController::class, 'createOrder'])->name('orders.create');
    Route::post('/orders', [StaffController::class, 'storeOrder'])->name('orders.store');
    Route::get('/orders/{order}/edit', [StaffController::class, 'editOrder'])->name('orders.edit');
    Route::put('/orders/{order}', [StaffController::class, 'updateOrder'])->name('orders.update');
    Route::delete('/orders/{order}', [StaffController::class, 'deleteOrder'])->name('orders.destroy');
    Route::get('/orders/{order}', [StaffController::class, 'showOrder'])->name('orders.show');

    // Order status management routes
    Route::put('/orders/{order}/complete', [StaffController::class, 'completeOrder'])->name('orders.complete');
    Route::put('/orders/{order}/mark-paid', [StaffController::class, 'markOrderPaid'])->name('orders.mark-paid');
    Route::put('/orders/{order}/mark-unpaid', [StaffController::class, 'markOrderUnpaid'])->name('orders.mark-unpaid');

    // Menu Items routes
    Route::get('/menu-items', [StaffController::class, 'manageMenuItems'])->name('menu-items.index');
    Route::get('/menu-items/create', [StaffController::class, 'createMenuItem'])->name('menu-items.create');
    Route::post('/menu-items', [StaffController::class, 'storeMenuItem'])->name('menu-items.store');
    Route::get('/menu-items/{menuItem}/edit', [StaffController::class, 'editMenuItem'])->name('menu-items.edit');
    Route::put('/menu-items/{menuItem}', [StaffController::class, 'updateMenuItem'])->name('menu-items.update');
    Route::delete('/menu-items/{menuItem}', [StaffController::class, 'destroyMenuItem'])->name('menu-items.destroy');

    // Billing Management
    Route::get('/billing', [StaffController::class, 'manageBilling'])->name('billing.index');
    Route::get('/billing/create', [StaffController::class, 'createBill'])->name('billing.create');
    Route::post('/billing', [StaffController::class, 'storeBill'])->name('billing.store');
    Route::put('/billing/{billing}/paid', [StaffController::class, 'markAsPaid'])->name('billing.paid');

    // Tenant Communication
    Route::get('/tenants', [StaffController::class, 'viewTenants'])->name('tenants.index');
    Route::get('/tenants/{tenant}', [StaffController::class, 'viewTenant'])->name('tenants.show');
    Route::patch('tenants/{tenant}/activate', [StaffController::class, 'activateTenant'])
        ->name('tenants.activate');

    Route::patch('tenants/{tenant}/deactivate', [StaffController::class, 'deactivateTenant'])
        ->name('tenants.deactivate');

    Route::patch('tenants/{tenant}/toggle-status', [StaffController::class, 'toggleTenantStatus'])
        ->name('tenants.toggle-status');

    Route::get('/tenant/create', [StaffController::class, 'createTenants'])->name('tenants.create');
    Route::post('/tenants', [StaffController::class, 'storeTenant'])->name('tenants.store');
    Route::get('/tenants/{user}/edit', [StaffController::class, 'editTenant'])->name('tenants.edit');
    Route::put('/tenants/{user}', [StaffController::class, 'updateTenant'])->name('tenants.update');
    Route::delete('/tenants/{user}', [StaffController::class, 'deleteTenant'])->name('tenants.destroy');



    Route::get('/tenants/{tenant}/details-api', [StaffController::class, 'getTenantRecord'])->name('tenants.details.api');
    Route::get('/tenants/{tenant}/balance-api', [StaffController::class, 'getTenantBalance'])->name('tenants.balance.api');
    Route::get('/tenants/{tenant}/report', [StaffController::class, 'generateTenantReport'])->name('tenants.report');
    Route::get('/tenants/export', [StaffController::class, 'exportTenants'])->name('tenants.export');
    Route::post('/tenants/bulk-update', [StaffController::class, 'bulkUpdateTenants'])->name('tenants.bulk-update');


    // Reports
    Route::get('/reports', [StaffController::class, 'viewReports'])->name('reports');
    Route::get('/reports/download', [StaffController::class, 'downloadReports'])->name('reports.download');


    // Order status
    Route::put('/orders/{order}/start-preparing', [StaffController::class, 'startPreparingOrder'])->name('orders.start-preparing');
    Route::put('/orders/{order}/mark-ready', [StaffController::class, 'markOrderReady'])->name('orders.mark-ready');


    // POS System Routes
    Route::get('/pos', [StaffController::class, 'posSystem'])->name('pos.index');
    Route::post('/pos/hold-order', [StaffController::class, 'holdOrder'])->name('pos.hold');
    Route::get('/pos/held-orders', [StaffController::class, 'getHeldOrders'])->name('pos.held-orders');
    Route::post('/pos/recall-order/{id}', [StaffController::class, 'recallOrder'])->name('pos.recall');
    Route::get('/tenant/{tenant}/unpaid-orders', [StaffController::class, 'getUnpaidOrders'])->name('tenant.unpaid-orders');
    // Quick actions for POS
    Route::post('/pos/quick-order', [StaffController::class, 'quickOrder'])->name('pos.quick-order');
    Route::get('/pos/daily-summary', [StaffController::class, 'dailySummary'])->name('pos.daily-summary');
    Route::get('/tenant/{tenant}/unpaid-orders', [StaffController::class, 'getUnpaidOrders']);

    // Enhanced Billing Management
    Route::prefix('billing')->name('billing.')->group(function () {
        // Main billing management page
        Route::get('/', [StaffController::class, 'manageBilling'])->name('index');

        // Create new billN
        Route::get('/create', [StaffController::class, 'createBill'])->name('create');
        Route::post('/store', [StaffController::class, 'storeBill'])->name('store');

        // View bill details
        Route::get('/{billing}', [StaffController::class, 'showBill'])->name('show');

        // Edit bill
        Route::get('/{billing}/edit', [StaffController::class, 'editBill'])->name('edit');
        Route::put('/{billing}', [StaffController::class, 'updateBill'])->name('update');

        // Bill actions
        Route::patch('/{billing}/mark-paid', [StaffController::class, 'markBillAsPaid'])->name('mark-paid');
        Route::patch('/{billing}/cancel', [StaffController::class, 'cancelBill'])->name('cancel');

        // Bulk operations
        Route::post('/generate-monthly', [StaffController::class, 'generateMonthlyRentBills'])->name('generate-monthly');
        Route::post('/send-reminders', [StaffController::class, 'sendPaymentReminders'])->name('send-reminders');
        Route::post('/update-overdue', [StaffController::class, 'updateOverdueBills'])->name('update-overdue');

        // Export and download
        Route::get('/export', [StaffController::class, 'exportBills'])->name('export');
        Route::get('/{billing}/pdf', [StaffController::class, 'downloadBillPDF'])->name('pdf');
        Route::get('/{billing}/invoice', [StaffController::class, 'downloadInvoice'])->name('invoice');

        // API
        Route::get('/stats/api', [StaffController::class, 'getBillingStats'])->name('stats.api');

        Route::get('/{bill}/invoice-data', [StaffController::class, 'getInvoiceData'])
            ->name('invoice.data');
        Route::get('/{bill}/receipt-data', [StaffController::class, 'getReceiptData'])
            ->name('receipt.data');

        // Download invoice/receipt as PDF
        Route::get('/{bill}/invoice/pdf', [StaffController::class, 'downloadInvoicePDF'])
            ->name('invoice.pdf');
        Route::get('/{bill}/receipt/pdf', [StaffController::class, 'downloadReceiptPDF'])
            ->name('receipt.pdf');

        // View invoice/receipt in browser (optional)
        Route::get('/{bill}/invoice', [StaffController::class, 'generateInvoice'])
            ->name('invoice.view');
        Route::get('/{bill}/receipt', [StaffController::class, 'generateReceipt'])
            ->name('receipt.view');

        // Email invoice/receipt (optional - requires mail setup)
        Route::post('/{bill}/invoice/email', [StaffController::class, 'emailInvoice'])
            ->name('invoice.email');
        Route::post('/{bill}/receipt/email', [StaffController::class, 'emailReceipt'])
            ->name('receipt.email');
        Route::post('/{bill}/send-invoice-email', [StaffController::class, 'sendInvoiceEmail'])
            ->name('send-invoice-email');

        Route::post('/{bill}/send-receipt-email', [StaffController::class, 'sendReceiptEmail'])
            ->name('send-receipt-email');


    });
    Route::get('/api/tenant/{id}/details', function ($id) {
        try {
            $tenant = \App\Models\User::where('role', 'tenant')->findOrFail($id);

            return response()->json([
                'id' => $tenant->id,
                'name' => $tenant->name,
                'email' => $tenant->email,
                'contact_number' => $tenant->contact_number
            ]);
        } catch (\Exception $e) {
            \Log::error('Tenant details API error', [
                'tenant_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Tenant not found'], 404);
        }
    })->name('api.tenant.details');

    Route::get('/api/tenant/{id}/details', [StaffController::class, 'getTenantDetailsForQR'])->name('api.tenant.details');

});

// Tenant Routes
// Replace the existing tenant routes section with this updated version:

// Tenant Routes
Route::middleware(['auth', TenantMiddleware::class])->prefix('tenant')->name('tenant.')->group(function () {
    Route::get('/dashboard', [TenantController::class, 'dashboard'])->name('dashboard');

    // Rent Management
    Route::get('/rent', [TenantController::class, 'viewRent'])->name('rent.index');
    Route::get('/rent/payment', [TenantController::class, 'rentPayment'])->name('rent.payment');
    Route::post('/rent/pay', [TenantController::class, 'payRent'])->name('rent.pay');
    Route::get('/rent/history', [TenantController::class, 'rentHistory'])->name('rent.history');

    // Maintenance Requests
    Route::get('/maintenance', [TenantController::class, 'viewMaintenance'])->name('maintenance.index');
    Route::get('/maintenance/create', [TenantController::class, 'createMaintenanceRequest'])->name('maintenance.create');
    Route::post('/maintenance', [TenantController::class, 'storeMaintenanceRequest'])->name('maintenance.store');
    Route::get('/maintenance/{maintenance}', [TenantController::class, 'showMaintenance'])->name('maintenance.show');

    // Cafe Orders - Enhanced
    Route::get('/orders', [TenantController::class, 'viewOrders'])->name('orders.index');
    Route::get('/orders/create', [TenantController::class, 'createOrder'])->name('orders.create'); // Redirects to menu
    Route::post('/orders', [TenantController::class, 'storeOrder'])->name('orders.store');
    Route::get('/orders/{order}', [TenantController::class, 'showOrder'])->name('orders.show');
    Route::delete('/orders/{order}/cancel', [TenantController::class, 'cancelOrder'])->name('orders.cancel');
    Route::post('/orders/{order}/reorder', [TenantController::class, 'reorderItems'])->name('orders.reorder');

    // Menu
    Route::get('/menu', [TenantController::class, 'viewMenu'])->name('menu');

    // Billing
    Route::get('/billing', [TenantController::class, 'viewBills'])->name('billing.index');
    Route::get('/billing/{billing}', [TenantController::class, 'showBill'])->name('billing.show');
    Route::post('/billing/{billing}/pay', [TenantController::class, 'payBill'])->name('billing.pay');

    // Profile Management
    Route::get('/profile', [TenantController::class, 'profile'])->name('profile');
    Route::put('/profile', [TenantController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [TenantController::class, 'updatePassword'])->name('profile.password');
});