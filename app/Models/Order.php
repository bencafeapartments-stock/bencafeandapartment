<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'staff_id',
        'amount',
        'quantity',
        'status',
        'special_instructions',
        'ordered_at',
        'prepared_at',
        'delivered_at',
        'delivery_fee',
        'billing_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'ordered_at' => 'datetime',
        'prepared_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    const STATUS_PAID = 'paid';
    const STATUS_UNPAID = 'unpaid';
    // Relationships
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePreparing($query)
    {
        return $query->where('status', 'preparing');
    }

    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['delivered']);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return '₱' . number_format((float) $this->amount, 2);
    }

    public function getFormattedDeliveryFeeAttribute()
    {
        return '₱' . number_format((float) $this->delivery_fee, 2);
    }

    public function getTotalAmountAttribute()
    {
        return (float) $this->amount + (float) $this->delivery_fee;
    }

    public function getFormattedTotalAmountAttribute()
    {
        return '₱' . number_format($this->total_amount, 2);
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'preparing' => 'blue',
            'ready' => 'green',
            'delivered' => 'gray',
            'cancelled' => 'red',
            default => 'gray'
        };
    }

    public function getStatusLabelAttribute()
    {
        return ucfirst($this->status);
    }

    public function getItemsSummaryAttribute()
    {
        return $this->orderItems->map(function ($item) {
            return $item->product->product_name . ' x' . $item->quantity;
        })->join(', ');
    }

    // Helper Methods
    public function markAsPreparing($staffId = null)
    {
        $this->update([
            'status' => 'preparing',
            'staff_id' => $staffId,
            'prepared_at' => now(),
        ]);
    }

    public function markAsReady()
    {
        $this->update(['status' => 'ready']);
    }

    public function markAsDelivered()
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'special_instructions' => $this->special_instructions . ($reason ? "\n\nCancelled: $reason" : ""),
        ]);
    }
}
