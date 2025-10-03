<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'apartment_number',
        'status',
        'price',
        'apartment_type',
        'description',
        'floor_number',
        'size_sqm',
        'amenities',
    ];

    protected $casts = [
        'amenities' => 'array',
        'price' => 'decimal:2',
        'size_sqm' => 'decimal:2',
    ];

    public function rent()
    {
        return $this->hasMany(Rent::class);
    }

    public function currentRent()
    {
        return $this->hasOne(Rent::class)
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>', now());
            });
    }
    public function maintenanceRequests()
    {
        return $this->hasMany(Maintenance::class);
    }


    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('apartment_type', $type);
    }


    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format((float) $this->price, 2);
    }

    public function getFormattedSizeAttribute()
    {
        return number_format((float) $this->size_sqm, 2) . ' sqm';
    }

    public function getIsAvailableAttribute()
    {
        return $this->status === 'available';
    }

    public function getIsOccupiedAttribute()
    {
        return $this->status === 'occupied';
    }

    public function getCurrentTenant()
    {
        $currentRent = $this->currentRent;
        return $currentRent ? $currentRent->tenant : null;
    }

    public function markAsOccupied()
    {
        $this->update(['status' => 'occupied']);
    }

    public function markAsAvailable()
    {
        $this->update(['status' => 'available']);
    }
}
