<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'business_id',
        'shop_product_id',
        'type',
        'direction',
        'quantity',
        'unit_cost',
        'stock_before',
        'stock_after',
        'reference_type',
        'reference_id',
        'note',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'unit_cost' => 'decimal:2',
        'stock_before' => 'decimal:3',
        'stock_after' => 'decimal:3',
    ];

    public function shopProduct()
    {
        return $this->belongsTo(ShopProduct::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}