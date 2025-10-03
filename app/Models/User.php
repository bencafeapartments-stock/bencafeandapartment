<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'contact_number',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }
    public function isAdmin(): bool
    {
        return $this->role === 'owner'; // Owner acts as admin
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isTenant(): bool
    {
        return $this->role === 'tenant';
    }

    // Additional role methods
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function hasAdminPrivileges(): bool
    {
        return in_array($this->role, ['owner', 'staff']);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeOwners($query)
    {
        return $query->where('role', 'owner');
    }

    public function scopeStaff($query)
    {
        return $query->where('role', 'staff');
    }

    public function scopeTenants($query)
    {
        return $query->where('role', 'tenant');
    }

    // TENANT RELATIONSHIPS
    public function tenantRents()
    {
        return $this->hasMany(Rent::class, 'tenant_id');
    }

    public function currentRent()
    {
        return $this->hasOne(Rent::class, 'tenant_id')
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>', now());
            });
    }

    public function tenantMaintenanceRequests()
    {
        return $this->hasMany(Maintenance::class, 'tenant_id');
    }
    public function apartment()
    {
        return $this->hasMany(Order::class, 'apartment_id');
    }
    public function tenantOrders()
    {
        return $this->hasMany(Order::class, 'tenant_id');
    }

    public function tenantBills()
    {
        return $this->hasMany(Billing::class, 'tenant_id');
    }

    public function tenantPayments()
    {
        return $this->hasMany(RentPayment::class, 'tenant_id');
    }
    public function Billings()
    {
        return $this->hasMany(Billing::class, 'tenant_id');
    }

    // STAFF RELATIONSHIPS
    public function assignedMaintenanceRequests()
    {
        return $this->hasMany(Maintenance::class, 'staff_id');
    }

    public function processedOrders()
    {
        return $this->hasMany(Order::class, 'staff_id');
    }

    public function managedRents()
    {
        return $this->hasMany(Rent::class, 'staff_id');
    }

    // HELPER METHODS FOR TENANTS
    public function getCurrentApartment()
    {
        $currentRent = $this->currentRent;
        return $currentRent ? $currentRent->apartment : null;
    }

    public function getMonthlyRent()
    {
        $currentRent = $this->currentRent;
        return $currentRent ? $currentRent->monthly_rent : 0;
    }

    public function getOutstandingBills()
    {
        return $this->tenantBills()->pending()->get();
    }

    public function getOverdueBills()
    {
        return $this->tenantBills()->overdue()->get();
    }

    public function getTotalOutstandingAmount()
    {
        return $this->getOutstandingBills()->sum('total_amount');
    }

    public function getActiveMaintenance()
    {
        return $this->tenantMaintenanceRequests()->inProgress()->get();
    }

    public function getRecentOrders($limit = 5)
    {
        return $this->tenantOrders()
            ->latest()
            ->limit($limit)
            ->with('orderItems.product')
            ->get();
    }

    public function getPaymentHistory($limit = 10)
    {
        return $this->tenantPayments()
            ->paid()
            ->latest('payment_date')
            ->limit($limit)
            ->with('rent.apartment')
            ->get();
    }

    // HELPER METHODS FOR STAFF
    public function getPendingMaintenanceTasks()
    {
        return $this->assignedMaintenanceRequests()
            ->whereIn('status', ['assigned', 'in_progress'])
            ->get();
    }

    public function getTodayOrders()
    {
        return $this->processedOrders()
            ->whereDate('created_at', today())
            ->get();
    }

    public function getMonthlyPerformance()
    {
        return [
            'completed_maintenance' => $this->assignedMaintenanceRequests()
                ->completed()
                ->whereMonth('completed_at', now()->month)
                ->count(),
            'processed_orders' => $this->processedOrders()
                ->whereMonth('created_at', now()->month)
                ->count(),
            'total_revenue' => $this->processedOrders()
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
        ];
    }

    // ACCESSORS
    public function getDisplayNameAttribute()
    {
        return $this->full_name ?: $this->name;
    }

    public function getRoleColorAttribute()
    {
        return match ($this->role) {
            'owner' => 'blue',
            'staff' => 'green',
            'tenant' => 'purple',
            default => 'gray'
        };
    }

    public function getRoleLabelAttribute()
    {
        return ucfirst($this->role);
    }

    public function getStatusBadgeAttribute()
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    public function getStatusColorAttribute()
    {
        return $this->is_active ? 'green' : 'red';
    }
}
