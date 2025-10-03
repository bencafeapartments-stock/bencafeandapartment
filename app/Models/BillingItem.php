<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingItem extends Model
{
    use HasFactory;

    protected $table = 'billing_items';

    protected $fillable = [
        'billing_id',
        'item_type',
        'reference_id',
        'amount',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];


    public function billing()
    {
        return $this->belongsTo(Billing::class, 'billing_id');
    }


}
