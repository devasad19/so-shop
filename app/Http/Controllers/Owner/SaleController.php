<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\BusinessUser;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use App\Models\ShopProduct;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
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
     * POS page
     */
public function create()
{
    $businessId = $this->businessId();

    $products = ShopProduct::with(['product.unit'])
        ->where('business_id', $businessId)
        ->where('status', 1)
        ->where('track_stock', true)
        ->where('current_stock', '>', 0)
        ->get()
        ->map(function ($shopProduct) {

            $product = $shopProduct->product;

            return [
                'id' => $shopProduct->id,

                // বাংলা নাম
                'name_bn' => $product?->name_bn ?? $product?->name ?? 'পণ্য',

                // Banglish নাম
                'name_bnlish' => $product?->name_bnlish ?? '',

                // English নাম
                'name_en' => $product?->name_en ?? '',

                'stock' => (float) $shopProduct->current_stock,
                'price' => (float) $shopProduct->selling_price,
                'cost' => (float) $shopProduct->purchase_price,

                'unit' => $product?->unit?->short_name ?? '',
                'image' => $product?->image ?? null,
            ];
        })
        ->values();

    return view('backend.owner.sales.create', compact('products'));
}

    /**
     * Save sale
     */
    public function store(Request $request)
    {
        $businessId = $this->businessId();

        $validated = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:30'],

            'discount' => ['nullable', 'numeric', 'min:0'],
            'paid_amount' => ['required', 'numeric', 'min:0'],

            'payment_method' => [
                'required',
                'in:cash,bkash,nagad,bank,other,due',
            ],

            'note' => ['nullable', 'string', 'max:1000'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.shop_product_id' => ['required', 'integer'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0', 'decimal:0,3'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
        ]);

        $sale = DB::transaction(function () use (
            $validated,
            $businessId
        ) {
            $subtotal = 0;
            $itemsData = [];

            // Lock and verify stock
            foreach ($validated['items'] as $item) {
                $shopProduct = ShopProduct::where('business_id', $businessId)
                    ->whereKey($item['shop_product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if (!$shopProduct->track_stock) {
                    throw ValidationException::withMessages([
                        'items' => 'একটি পণ্যের Stock Tracking বন্ধ আছে।',
                    ]);
                }

                $quantity = (float) $item['quantity'];
                $unitPrice = (float) $item['unit_price'];

                if ($quantity > (float) $shopProduct->current_stock) {
                    throw ValidationException::withMessages([
                        'items' => ($shopProduct->product?->name ?? 'পণ্য')
                            . ' এর পর্যাপ্ত Stock নেই। বর্তমান Stock: '
                            . $shopProduct->current_stock,
                    ]);
                }

                $lineTotal = round($quantity * $unitPrice, 2);
                $subtotal += $lineTotal;

                $itemsData[] = [
                    'shopProduct' => $shopProduct,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $lineTotal,
                ];
            }

            $subtotal = round($subtotal, 2);
            $discount = (float) ($validated['discount'] ?? 0);

            if ($discount > $subtotal) {
                throw ValidationException::withMessages([
                    'discount' => 'ছাড় মোট বিক্রির চেয়ে বেশি হতে পারবে না।',
                ]);
            }

            $total = round($subtotal - $discount, 2);
            $paidAmount = (float) $validated['paid_amount'];

            if ($paidAmount > $total) {
                throw ValidationException::withMessages([
                    'paid_amount' => 'পরিশোধিত টাকা মোট বিলের চেয়ে বেশি হতে পারবে না।',
                ]);
            }

            $dueAmount = round($total - $paidAmount, 2);

            $invoiceNumber = 'INV-' . date('ymd') . '-'
                . strtoupper(substr(uniqid(), -6));

            $sale = Sale::create([
                'business_id' => $businessId,
                'invoice_number' => $invoiceNumber,

                'customer_name' => $validated['customer_name'] ?? null,
                'customer_phone' => $validated['customer_phone'] ?? null,

                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,

                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,

                'payment_method' => $validated['payment_method'],
                'status' => 'completed',

                'note' => $validated['note'] ?? null,
                'sold_by' => auth()->id(),
            ]);

            foreach ($itemsData as $data) {
                $shopProduct = $data['shopProduct'];
                $quantity = $data['quantity'];

                $before = (float) $shopProduct->current_stock;
                $after = round($before - $quantity, 3);

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'shop_product_id' => $shopProduct->id,

                    'product_name' => $shopProduct->product?->name ?? 'Product',
                    'unit_name' => $shopProduct->product?->unit?->short_name,

                    'quantity' => $quantity,
                    'unit_price' => $data['unit_price'],
                    'purchase_cost' => (float) $shopProduct->purchase_price,

                    'subtotal' => $data['subtotal'],
                ]);

                $shopProduct->current_stock = $after;
                $shopProduct->save();

                StockMovement::create([
                    'business_id' => $businessId,
                    'shop_product_id' => $shopProduct->id,

                    'type' => 'sale',
                    'direction' => 'out',
                    'quantity' => $quantity,

                    'unit_cost' => (float) $shopProduct->purchase_price,
                    'stock_before' => $before,
                    'stock_after' => $after,

                    'reference_type' => Sale::class,
                    'reference_id' => $sale->id,

                    'note' => 'Sale ' . $sale->invoice_number,
                    'created_by' => auth()->id(),
                ]);
            }

            // Record initial payment
            if ($paidAmount > 0) {
                SalePayment::create([
                    'sale_id' => $sale->id,
                    'amount' => $paidAmount,
                    'payment_method' => $validated['payment_method'],
                    'note' => 'Initial payment',
                    'received_by' => auth()->id(),
                ]);
            }

            return $sale;
        });

        return redirect()
            ->route('owner.sales.show', $sale)
            ->with('success', 'বিক্রি সফলভাবে সম্পন্ন হয়েছে।');
    }

    /**
     * Sales history
     */
    public function index(Request $request)
    {
        $businessId = $this->businessId();

        $query = Sale::where('business_id', $businessId);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $sales = $query->latest()
            ->paginate(20)
            ->withQueryString();

        $totalSales = Sale::where('business_id', $businessId)
            ->where('status', 'completed')
            ->sum('total');

        $totalPaid = Sale::where('business_id', $businessId)
            ->where('status', 'completed')
            ->sum('paid_amount');

        $totalDue = Sale::where('business_id', $businessId)
            ->where('status', 'completed')
            ->sum('due_amount');

        return view('backend.owner.sales.index', compact(
            'sales',
            'totalSales',
            'totalPaid',
            'totalDue'
        ));
    }

    /**
     * Sale details
     */
    public function show(Sale $sale)
    {
        abort_unless(
            $sale->business_id == $this->businessId(),
            403
        );

        $sale->load([
            'items',
            'payments.receivedBy',
            'soldBy',
        ]);

        return view('backend.owner.sales.show', compact('sale'));
    }

    /**
     * Printable invoice
     */
    public function invoice(Sale $sale)
    {
        abort_unless(
            $sale->business_id == $this->businessId(),
            403
        );

        $sale->load(['items', 'payments']);

        return view('backend.owner.sales.invoice', compact('sale'));
    }
}
