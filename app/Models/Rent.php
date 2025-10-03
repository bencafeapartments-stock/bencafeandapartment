<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Rent extends Model
{
    use HasFactory;

    protected $table = 'rent';

    protected $fillable = [
        'tenant_id',
        'apartment_id',
        'staff_id',
        'monthly_rent',
        'start_date',
        'end_date',
        'status',
        'terms',
        'security_deposit',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'monthly_rent' => 'decimal:2',
        'security_deposit' => 'decimal:2',
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function rentPayments()
    {
        return $this->hasMany(RentPayment::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>', now());
            });
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')->orWhere('end_date', '<', now());
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    // Accessors
    public function getFormattedMonthlyRentAttribute()
    {
        return '₱' . number_format((float) $this->monthly_rent, 2);
    }

    public function getFormattedSecurityDepositAttribute()
    {
        return '₱' . number_format((float) $this->security_deposit, 2);
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active' &&
            ($this->end_date === null || $this->end_date->isFuture());
    }

    public function getIsExpiredAttribute()
    {
        return $this->end_date && $this->end_date->isPast();
    }

    public function getDurationInMonthsAttribute()
    {
        $start = $this->start_date;
        $end = $this->end_date ?? now();
        return $start->diffInMonths($end);
    }

    // Helper Methods
    public function getNextDueDate(): Carbon
    {
        $lastPayment = $this->rentPayments()
            ->where('status', 'paid')
            ->orderBy('payment_date', 'desc')
            ->first();

        if ($lastPayment) {
            return $lastPayment->payment_date->copy()->addMonths(1);
        }

        return $this->start_date;
    }

    public function getOutstandingAmount()
    {
        $monthsOwed = $this->getMonthsOwed();
        return $monthsOwed * (float) $this->monthly_rent;
    }

    public function getMonthsOwed()
    {
        $nextDue = $this->getNextDueDate();
        if ($nextDue->isFuture()) {
            return 0;
        }
        return $nextDue->diffInMonths(now()) + 1;
    }

    public function terminate($reason = null)
    {
        $this->update([
            'status' => 'terminated',
            'end_date' => now(),
            'terms' => $this->terms . "\n\nTerminated on " . now()->format('Y-m-d') .
                ($reason ? ". Reason: $reason" : "")
        ]);

        $this->apartment->markAsAvailable();
    }
}
