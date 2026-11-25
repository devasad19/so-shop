<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\BusinessUser;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RuntimeException;

class StockMovementController extends Controller
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
     * Stock History
     */
    public function index(Request $request)
    {
        $businessId = $this->businessId();

        $query = StockMovement::with(['product', 'creator'])
            ->where('business_id', $businessId);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $movements = $query->latest('id')
            ->paginate(20)
            ->withQueryString();

        $products = Product::where('business_id', $businessId)
            ->where('status', true)
            ->where('track_stock', true)
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'current_stock']);

        return view('backend.owner.stock_movements.index', compact(
            'movements',
            'products'
        ));
    }

    /**
     * Stock Adjustment
     */
    public function storeAdjustment(
        Request $request,
        StockService $stockService
    ) {
        $businessId = $this->businessId();

        $validated = $request->validate([
            'product_id' => [
                'required',
                Rule::exists('products', 'id')
                    ->where('business_id', $businessId),
            ],
            'direction' => ['required', Rule::in(['in', 'out'])],
            'quantity' => ['required', 'numeric', 'gt:0', 'decimal:0,3'],
            'unit_cost' => ['nullable', 'numeric', 'min:0', 'decimal:0,2'],
            'note' => ['required', 'string', 'max:500'],
        ], [
            'product_id.required' => 'একটি পণ্য নির্বাচন করুন।',
            'direction.required' => 'স্টক পরিবর্তনের ধরন নির্বাচন করুন।',
            'quantity.required' => 'পরিমাণ লিখুন।',
            'quantity.gt' => 'পরিমাণ শূন্যের বেশি হতে হবে।',
            'note.required' => 'স্টক পরিবর্তনের কারণ লিখুন।',
        ]);

        try {
            $stockService->adjust(
                businessId: $businessId,
                productId: (int) $validated['product_id'],
                direction: $validated['direction'],
                quantity: (float) $validated['quantity'],
                unitCost: (float) ($validated['unit_cost'] ?? 0),
                note: $validated['note'],
                userId: auth()->id()
            );

            return back()->with('success', 'স্টক সফলভাবে সংশোধন করা হয়েছে।');

        } catch (RuntimeException $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}