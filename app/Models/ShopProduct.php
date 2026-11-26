<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopProduct extends Model
{
    protected $fillable = [
        'business_id',
        'product_id',
        'purchase_price',
        'selling_price',
        'current_stock',
        'alert_quantity',
        'track_stock',
        'status',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'current_stock' => 'decimal:3',
        'alert_quantity' => 'decimal:3',
        'track_stock' => 'boolean',
        'status' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function purchases()
    {
        return $this->hasMany(ShopPurchase::class);
    }
}