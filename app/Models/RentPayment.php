<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'rent_id',
        'tenant_id',
        'billing_id',
        'payment_date',
        'amount',
        'payment_method',
        'status',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function rent()
    {
        return $this->belongsTo(Rent::class);
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeForRent($query, $rentId)
    {
        return $query->where('rent_id', $rentId);
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return '₱' . number_format((float) $this->amount, 2);
    }

    public function getPaymentMethodLabelAttribute()
    {
        return match ($this->payment_method) {
            'bank_transfer' => 'Bank Transfer',
            'gcash' => 'GCash',
            'card' => 'Credit/Debit Card',
            'cash' => 'Cash',
            'other' => 'Other',
            default => ucfirst($this->payment_method)
        };
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'paid' => 'green',
            'failed' => 'red',
            'refunded' => 'blue',
            default => 'gray'
        };
    }

    public function getStatusLabelAttribute()
    {
        return ucfirst($this->status);
    }

    // Helper Methods
    public function markAsPaid()
    {
        $this->update(['status' => 'paid']);

        // Update related billing if exists
        if ($this->billing) {
            $this->billing->markAsPaid($this->payment_method, $this->reference_number, $this->notes);
        }
    }

    public function markAsFailed($reason = null)
    {
        $this->update([
            'status' => 'failed',
            'notes' => $this->notes . ($reason ? "\n\nFailed: $reason" : ""),
        ]);
    }

    public function refund($reason = null)
    {
        $this->update([
            'status' => 'refunded',
            'notes' => $this->notes . ($reason ? "\n\nRefunded: $reason" : ""),
        ]);
    }
}