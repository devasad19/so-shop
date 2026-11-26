<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Business extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'business_type',
        'owner_user_id',
        'phone',
        'email',
        'address',
        'logo',
        'currency',
        'status',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'business_users')
            ->withPivot(['role', 'status', 'joined_at'])
            ->withTimestamps();
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(BusinessUser::class);
    }


public function productCategories()
{
    return $this->hasMany(\App\Models\ProductCategory::class);
}

public function units()
{
    return $this->hasMany(\App\Models\Unit::class);
}

public function products()
{
    return $this->hasMany(\App\Models\Product::class);
}

public function stockMovements()
{
    return $this->hasMany(\App\Models\StockMovement::class);
}
public function shopProducts()
{
    return $this->hasMany(\App\Models\ShopProduct::class);
}

public function shopPurchases()
{
    return $this->hasMany(\App\Models\ShopPurchase::class);
}
 



}