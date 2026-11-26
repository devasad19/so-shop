<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\BusinessUser;
use App\Models\Product;
use App\Models\ShopProduct;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ShopProductController extends Controller
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

    /**
     * Global Catalog
     */

public function catalog(Request $request)
{
$businessId = $this->businessId();

$categories = ProductCategory::withCount([
    'products as total_products' => function ($q) {
        $q->where('status', true);
    }
])->orderBy('name')->get();

$claimedCounts = ShopProduct::query()
    ->join('products', 'products.id', '=', 'shop_products.product_id')
    ->where('shop_products.business_id', $businessId)
    ->selectRaw('products.category_id, COUNT(shop_products.id) as total')
    ->groupBy('products.category_id')
    ->pluck('total', 'category_id');

foreach ($categories as $category) {
    $category->claimed_count = (int) ($claimedCounts[$category->id] ?? 0);
    $category->available_count = max(
        0,
        (int) $category->total_products - $category->claimed_count
    );
}

$claimedIds = ShopProduct::where('business_id', $businessId)
    ->pluck('product_id');

$totalCatalogProducts = Product::where('status', true)->count();
$totalClaimed = $claimedIds->count();
$totalAvailable = max(0, $totalCatalogProducts - $totalClaimed);

    $query = \App\Models\Product::with(['category', 'unit'])
        ->where('status', true)
        ->whereNotIn('id', $claimedIds);

    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }

    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('sku', 'like', '%' . $search . '%')
                ->orWhere('barcode', 'like', '%' . $search . '%');
        });
    }

    $products = $query->orderBy('name')
        ->paginate(30)
        ->withQueryString();

    $totalCatalogProducts = \App\Models\Product::where('status', true)->count();

    $totalClaimed = \App\Models\ShopProduct::where(
        'business_id',
        $businessId
    )->count();

    return view('backend.owner.shop_products.catalog', compact(
        'products',
        'categories',
        'totalCatalogProducts',
        'totalClaimed',
        'totalAvailable'
    ));
}


    /**
     * Claim Selected Products
     */
    public function claim(Request $request)
    {
        $businessId = $this->businessId();

        $validated = $request->validate([
            'product_ids' => ['required', 'array', 'min:1'],
            'product_ids.*' => [
                'required',
                'integer',
                Rule::exists('products', 'id')->where('status', true),
            ],
        ], [
            'product_ids.required' => 'পণ্য নির্বাচন করুন।',
            'product_ids.min' => 'কমপক্ষে একটি পণ্য নির্বাচন করুন।',
        ]);

        DB::transaction(function () use ($validated, $businessId) {

            $products = Product::whereIn('id', $validated['product_ids'])
                ->where('status', true)
                ->lockForUpdate()
                ->get();

            foreach ($products as $product) {

                ShopProduct::firstOrCreate(
                    [
                        'business_id' => $businessId,
                        'product_id' => $product->id,
                    ],
                    [
                        'purchase_price' => 0,
                        'selling_price' => 0,
                        'current_stock' => 0,
                        'alert_quantity' => 0,
                        'track_stock' => true,
                        'status' => true,
                    ]
                );
            }
        });

        return redirect()
            ->route('owner.shop-products.index')
            ->with('success', 'নির্বাচিত পণ্যগুলো আপনার দোকানে যোগ হয়েছে।');
    }

    /**
     * Shop Product List
     */
  
public function index(Request $request)
{
    $businessId = $this->businessId();

    $categories = ProductCategory::orderBy('name')->get();

    $claimedCounts = ShopProduct::query()
        ->join('products', 'products.id', '=', 'shop_products.product_id')
        ->where('shop_products.business_id', $businessId)
        ->selectRaw('products.category_id, COUNT(shop_products.id) as total')
        ->groupBy('products.category_id')
        ->pluck('total', 'category_id');

    foreach ($categories as $category) {
        $category->claimed_count = (int) ($claimedCounts[$category->id] ?? 0);
    }

    $totalShopProducts = ShopProduct::where('business_id', $businessId)->count();

    $query = \App\Models\ShopProduct::with([
        'product.category',
        'product.unit'
    ])
        ->where('business_id', $businessId);

    if ($request->filled('category')) {
        $query->whereHas('product', function ($q) use ($request) {
            $q->where('category_id', $request->category);
        });
    }

    if ($request->filled('search')) {
        $search = $request->search;

        $query->whereHas('product', function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('sku', 'like', '%' . $search . '%')
                ->orWhere('barcode', 'like', '%' . $search . '%');
        });
    }

    $shopProducts = $query->latest('id')
        ->paginate(30)
        ->withQueryString();

    $totalShopProducts = \App\Models\ShopProduct::where(
        'business_id',
        $businessId
    )->count();

    $totalCatalogProducts = \App\Models\Product::where('status', true)->count();

    return view('backend.owner.shop_products.index', compact(
        'shopProducts',
        'categories',
        'totalShopProducts'
    ));
}


    /**
     * Product Details
     */
    public function show(ShopProduct $shopProduct)
    {
        abort_unless($shopProduct->business_id === $this->businessId(), 403);

        $shopProduct->load([
            'product.category',
            'product.unit',
            'purchases',
            'stockMovements.creator',
        ]);

        return view('backend.owner.shop_products.show', compact(
            'shopProduct'
        ));
    }

    /**
     * Remove from Shop
     */
    public function destroy(ShopProduct $shopProduct)
    {
        abort_unless($shopProduct->business_id === $this->businessId(), 403);

        if (
            (float) $shopProduct->current_stock != 0 ||
            $shopProduct->purchases()->exists() ||
            $shopProduct->stockMovements()->exists()
        ) {
            return back()->with(
                'error',
                'এই পণ্যের Stock অথবা History আছে। Remove করা যাবে না।'
            );
        }

        $shopProduct->delete();

        return back()->with('success', 'পণ্যটি আপনার Shop List থেকে Remove করা হয়েছে।');
    }
}