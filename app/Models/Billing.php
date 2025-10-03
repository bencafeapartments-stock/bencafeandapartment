<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Billing extends Model
{
    use HasFactory;

    protected $table = 'billing';

    protected $fillable = [
        'tenant_id',
        'rent_id',
        'order_id',
        'billing_type',
        'amount',
        'due_date',
        'issued_date',
        'paid_date',
        'status',
        'description',
        'late_fee',
        'line_items',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'due_date' => 'datetime',
        'issued_date' => 'datetime',
        'paid_date' => 'datetime',
        'line_items' => 'array',
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function rent()
    {
        return $this->belongsTo(Rent::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function rentPayments()
    {
        return $this->hasMany(RentPayment::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    public function billingItems()
    {
        return $this->hasMany(BillingItem::class, 'billing_id');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
            ->orWhere(function ($query) {
                $query->where('status', 'pending')
                    ->where('due_date', '<', now());
            });
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('billing_type', $type);
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return '₱' . number_format((float) $this->amount, 2);
    }

    public function getFormattedLateFeeAttribute()
    {
        return '₱' . number_format((float) $this->late_fee, 2);
    }

    public function getTotalAmountAttribute()
    {
        return (float) $this->amount + (float) $this->late_fee;
    }

    public function getFormattedTotalAmountAttribute()
    {
        return '₱' . number_format($this->total_amount, 2);
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'paid' => 'green',
            'overdue' => 'red',
            'cancelled' => 'gray',
            default => 'gray'
        };
    }

    public function getStatusLabelAttribute()
    {
        return ucfirst($this->status);
    }

    public function getIsOverdueAttribute()
    {
        return $this->status === 'pending' && $this->due_date && $this->due_date->isPast();
    }

    public function getDaysOverdueAttribute()
    {
        if (!$this->is_overdue) {
            return 0;
        }
        return $this->due_date->diffInDays(now());
    }

    public function getBillingTypeLabelAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->billing_type));
    }

    // Helper Methods
    public function markAsPaid($paymentMethod = 'cash', $referenceNumber = null, $notes = null)
    {
        $this->update([
            'status' => 'paid',
            'paid_date' => now()  // Set the paid_date when marking as paid
        ]);

        // Create payment record if it's a rent bill
        if ($this->rent_id && $this->billing_type === 'rent') {
            RentPayment::create([
                'rent_id' => $this->rent_id,
                'tenant_id' => $this->tenant_id,
                'billing_id' => $this->id,
                'payment_date' => now(),
                'amount' => $this->total_amount,
                'payment_method' => $paymentMethod,
                'status' => 'paid',
                'reference_number' => $referenceNumber,
                'notes' => $notes,
            ]);
        }
    }

    public function markAsOverdue()
    {
        if ($this->status === 'pending' && $this->due_date && $this->due_date->isPast()) {
            $this->update(['status' => 'overdue']);
            $this->calculateLateFee();
        }
    }

    public function calculateLateFee($dailyRate = 50)
    {
        if ($this->is_overdue) {
            $lateFee = $this->days_overdue * $dailyRate;
            $this->update(['late_fee' => $lateFee]);
        }
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'description' => $this->description . ($reason ? "\n\nCancelled: $reason" : ""),
        ]);
    }

    // Static Methods
    public static function generateRentBill($rent, $dueDate = null)
    {
        $dueDate = $dueDate ?? now()->addMonths(1);

        return self::create([
            'tenant_id' => $rent->tenant_id,
            'rent_id' => $rent->id,
            'billing_type' => 'rent',
            'amount' => $rent->monthly_rent,
            'due_date' => $dueDate,
            'issued_date' => now(),
            'status' => 'pending',
            'description' => "Monthly rent for apartment {$rent->apartment->apartment_number}",
        ]);
    }

    public static function generateOrderBill($order)
    {
        return self::create([
            'tenant_id' => $order->tenant_id,
            'order_id' => $order->id,
            'billing_type' => 'cafe',
            'amount' => $order->total_amount,
            'due_date' => now()->addDays(7),
            'issued_date' => now(),
            'status' => 'pending',
            'description' => "Cafe order #{$order->id}",
            'line_items' => $order->orderItems->map(function ($item) {
                return [
                    'name' => $item->product->product_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total' => $item->total_price,
                ];
            })->toArray(),
        ]);
    }
}