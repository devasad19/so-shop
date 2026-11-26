<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopPurchase extends Model
{
    protected $fillable = [
        'business_id',
        'shop_product_id',
        'pack_name',
        'pack_size',
        'pack_quantity',
        'price_per_pack',
        'total_quantity',
        'unit_cost',
        'total_amount',
        'note',
        'created_by',
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