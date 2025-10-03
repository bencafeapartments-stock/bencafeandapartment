<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'description',
        'price',
        'category',
        'is_available',
        'image_url',
        'ingredients',
        'prep_time_minutes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'ingredients' => 'array',
    ];

    // Relationships
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format((float) $this->price, 2);
    }

    public function getCategoryLabelAttribute()
    {
        return ucfirst($this->category);
    }
}