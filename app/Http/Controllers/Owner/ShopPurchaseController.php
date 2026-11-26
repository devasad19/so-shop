<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\BusinessUser;
use App\Models\ShopProduct;
use App\Models\ShopPurchase;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Supplier;

class ShopPurchaseController extends Controller
{
    private function businessId(): int
    {
        $membership = BusinessUser::where('user_id', auth()->id())
            ->where('role', 'owner')
            ->where('status', 'active')
            ->first();

        abort_unless($membership, 403);

        return (int) $membership->business_id;
    }

public function create(Request $request, ShopProduct $shopProduct)
{
    $businessId = $this->businessId();

    // Shop ownership check
    abort_unless(
        $shopProduct->business_id == $businessId,
        403
    );

    $shopProduct->load([
        'product.category',
        'product.unit',
    ]);

    $suppliers = Supplier::where('business_id', $businessId)
        ->where('status', true)
        ->orderBy('name')
        ->get();

    return view(
        'backend.owner.shop_products.purchase',
        compact('shopProduct', 'suppliers')
    );
}
    /**
     * Store Purchase
     */
/**
 * Store Purchase
 */
public function store(Request $request, ShopProduct $shopProduct)
{
    $businessId = $this->businessId();

    abort_unless(
        (int) $shopProduct->business_id === $businessId,
        403
    );

    $validated = $request->validate([
        'pack_size'     => ['required', 'numeric', 'gt:0', 'decimal:0,3'],
        'pack_quantity' => ['required', 'numeric', 'gt:0', 'decimal:0,3'],

        'purchase_price' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
        'selling_price'  => ['required', 'numeric', 'min:0', 'decimal:0,2'],

        'supplier_id' => [
            'nullable',
            'integer',
            'exists:suppliers,id',
        ],

        'note' => ['nullable', 'string', 'max:500'],
    ]);

    DB::transaction(function () use (
        $validated,
        $shopProduct,
        $businessId
    ) {
        $shopProduct = ShopProduct::where('business_id', $businessId)
            ->whereKey($shopProduct->id)
            ->lockForUpdate()
            ->firstOrFail();

        if (!$shopProduct->track_stock) {
            throw ValidationException::withMessages([
                'pack_quantity' => 'এই পণ্যের Stock Tracking বন্ধ আছে।',
            ]);
        }

        // Purchase calculation
        $packQuantity = (float) $validated['pack_quantity'];
        $packSize = (float) $validated['pack_size'];

        // Purchase price per base unit
        $purchasePrice = (float) $validated['purchase_price'];

        // Selling price per base unit
        $sellingPrice = (float) $validated['selling_price'];

        // Total quantity in product's original unit
        $totalQuantity = round($packQuantity * $packSize, 3);

        if ($totalQuantity <= 0) {
            throw ValidationException::withMessages([
                'pack_quantity' => 'ক্রয়ের পরিমাণ সঠিক নয়।',
            ]);
        }

        // Total purchase amount
        $totalAmount = round($totalQuantity * $purchasePrice, 2);

        // Total price per pack
        $pricePerPack = round($packSize * $purchasePrice, 2);

        // Purchase cost per original unit
        $unitCost = $purchasePrice;

        // Automatically determine pack name
        $unitName = $shopProduct->product?->unit?->short_name ?? 'unit';

        $packName = $packSize == 1
            ? $unitName . ' (খোলা)'
            : $packSize . ' ' . $unitName . ' (বস্তা)';

        // Stock calculation
        $before = (float) $shopProduct->current_stock;
        $after = round($before + $totalQuantity, 3);

        // Create purchase record
        $purchase = ShopPurchase::create([
            'business_id'     => $businessId,
            'shop_product_id' => $shopProduct->id,

            'pack_quantity'   => $packQuantity,
            'pack_size'       => $packSize,
            'total_quantity'  => $totalQuantity,

            'price_per_pack'  => $pricePerPack,
            'unit_cost'        => $unitCost,
            'total_amount'     => $totalAmount,

            'pack_name'        => $packName,
            'note'             => $validated['note'] ?? null,

            'created_by'       => auth()->id(),
        ]);

        // Update shop product stock & prices
        $shopProduct->current_stock = $after;
        $shopProduct->purchase_price = $purchasePrice;
        $shopProduct->selling_price = $sellingPrice;
        $shopProduct->save();

        // Stock movement
        StockMovement::create([
            'business_id'     => $businessId,
            'shop_product_id' => $shopProduct->id,

            'type'            => 'purchase',
            'direction'        => 'in',
            'quantity'         => $totalQuantity,

            'unit_cost'        => $unitCost,
            'stock_before'     => $before,
            'stock_after'      => $after,

            'reference_type'   => ShopPurchase::class,
            'reference_id'     => $purchase->id,

            'note'             => $validated['note'] ?? 'Purchase',
            'created_by'       => auth()->id(),
        ]);
    });

    return redirect()
        ->route('owner.shop-products.index')
        ->with('success', 'পণ্য ক্রয় ও স্টক আপডেট সফল হয়েছে।');
}
public function index(Request $request)
{
    $businessId = $this->businessId();

    $query = ShopPurchase::with([
        'shopProduct.product.unit',
        'creator'
    ])
    ->where('business_id', $businessId);

    // পণ্যের নাম দিয়ে সার্চ
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('pack_name', 'like', "%{$search}%")
              ->orWhereHas('shopProduct.product', function ($product) use ($search) {
                  $product->where('name', 'like', "%{$search}%");
              });
        });
    }

    // তারিখ অনুযায়ী
    if ($request->filled('from')) {
        $query->whereDate('created_at', '>=', $request->from);
    }

    if ($request->filled('to')) {
        $query->whereDate('created_at', '<=', $request->to);
    }

    $purchases = $query
        ->latest()
        ->paginate(20)
        ->withQueryString();

    $totalAmount = ShopPurchase::where('business_id', $businessId)
        ->sum('total_amount');

    $totalQuantity = ShopPurchase::where('business_id', $businessId)
        ->sum('total_quantity');

    return view('backend.owner.purchases.index', compact(
        'purchases',
        'totalAmount',
        'totalQuantity'
    ));
}


public function show(ShopPurchase $purchase)
{
    $businessId = $this->businessId();

    abort_unless(
        $purchase->business_id == $businessId,
        403
    );

    $purchase->load([
        'shopProduct.product.unit',
        'creator'
    ]);

    return view('backend.owner.purchases.show', compact('purchase'));
}

}