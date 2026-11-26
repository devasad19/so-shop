<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id',
        'shop_product_id',
        'product_name',
        'unit_name',
        'quantity',
        'unit_price',
        'purchase_cost',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'purchase_cost' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function shopProduct()
    {
        return $this->belongsTo(ShopProduct::class);
    }
}
