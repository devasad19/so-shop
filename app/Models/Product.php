<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'business_id',
        'category_id',
        'unit_id',
        'name',
        'sku',
        'barcode',
        'image',
        'description',
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

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}
