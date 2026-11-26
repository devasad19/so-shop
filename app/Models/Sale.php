<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'business_id',
        'invoice_number',
        'customer_name',
        'customer_phone',
        'subtotal',
        'discount',
        'total',
        'paid_amount',
        'due_amount',
        'payment_method',
        'status',
        'note',
        'sold_by',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payments()
    {
        return $this->hasMany(SalePayment::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function soldBy()
    {
        return $this->belongsTo(User::class, 'sold_by');
    }
}
