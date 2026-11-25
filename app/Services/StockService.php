<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class StockService
{
    /**
     * সাধারণ Stock Adjustment.
     * direction: in অথবা out
     */
    public function adjust(
        int $businessId,
        int $productId,
        string $direction,
        float $quantity,
        float $unitCost,
        ?string $note,
        ?int $userId
    ): StockMovement {
        return DB::transaction(function () use (
            $businessId,
            $productId,
            $direction,
            $quantity,
            $unitCost,
            $note,
            $userId
        ) {
            $product = Product::where('business_id', $businessId)
                ->whereKey($productId)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$product->track_stock) {
                throw new RuntimeException('এই পণ্যের Stock Tracking বন্ধ আছে।');
            }

            $before = (float) $product->current_stock;

            $after = $direction === 'in'
                ? $before + $quantity
                : $before - $quantity;

            $after = round($after, 3);

            if ($after < 0) {
                throw new RuntimeException('পর্যাপ্ত স্টক নেই। বর্তমান স্টকের চেয়ে বেশি কমানো যাবে না।');
            }

            $product->current_stock = $after;
            $product->save();

            return StockMovement::create([
                'business_id' => $businessId,
                'product_id' => $product->id,
                'type' => 'adjustment',
                'direction' => $direction,
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'stock_before' => $before,
                'stock_after' => $after,
                'note' => $note,
                'created_by' => $userId,
            ]);
        });
    }

    /**
     * প্রথমবার Opening Stock যোগ করার জন্য।
     * Product তৈরির সময় ভবিষ্যতে এই method ব্যবহার হবে।
     */
    public function addOpeningStock(
        int $businessId,
        int $productId,
        float $quantity,
        float $unitCost,
        ?string $note,
        ?int $userId
    ): StockMovement {
        return DB::transaction(function () use (
            $businessId,
            $productId,
            $quantity,
            $unitCost,
            $note,
            $userId
        ) {
            $product = Product::where('business_id', $businessId)
                ->whereKey($productId)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$product->track_stock) {
                throw new RuntimeException('এই পণ্যের Stock Tracking বন্ধ আছে।');
            }

            $alreadyOpened = StockMovement::where('business_id', $businessId)
                ->where('product_id', $product->id)
                ->where('type', 'opening')
                ->exists();

            if ($alreadyOpened || (float) $product->current_stock !== 0.0) {
                throw new RuntimeException('এই পণ্যের Opening Stock আগে থেকেই দেওয়া হয়েছে।');
            }

            $before = (float) $product->current_stock;
            $after = round($before + $quantity, 3);

            $product->current_stock = $after;
            $product->save();

            return StockMovement::create([
                'business_id' => $businessId,
                'product_id' => $product->id,
                'type' => 'opening',
                'direction' => 'in',
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'stock_before' => $before,
                'stock_after' => $after,
                'note' => $note,
                'created_by' => $userId,
            ]);
        });
    }
}