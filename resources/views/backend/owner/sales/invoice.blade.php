
@extends('apps.shop_master')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="flex justify-end mb-4 gap-2 print:hidden">

        <a href="{{ route('owner.sales.show', $sale) }}"
           class="px-4 py-2 bg-gray-100 rounded-xl">
            Back
        </a>

        <button onclick="window.print()"
                class="px-4 py-2 bg-emerald-600 text-white rounded-xl">
            Print Invoice
        </button>

    </div>

    <div class="bg-white border rounded-2xl p-5 sm:p-8 print:border-0 print:rounded-none">

        <div class="text-center border-b pb-5">

            <h1 class="text-2xl font-bold">Sales Invoice</h1>

            <p class="text-gray-500 mt-1">
                {{ $sale->invoice_number }}
            </p>

        </div>

        <div class="grid grid-cols-2 gap-4 py-5 text-sm">

            <div>
                <div class="text-gray-500">Customer</div>
                <div class="font-semibold">
                    {{ $sale->customer_name ?: 'Walk-in Customer' }}
                </div>
            </div>

            <div>
                <div class="text-gray-500">Phone</div>
                <div>{{ $sale->customer_phone ?: '-' }}</div>
            </div>

            <div>
                <div class="text-gray-500">Date</div>
                <div>{{ $sale->created_at->format('d M Y, h:i A') }}</div>
            </div>

            <div>
                <div class="text-gray-500">Payment</div>
                <div>{{ ucfirst($sale->payment_method) }}</div>
            </div>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>
                    <tr class="border-y">
                        <th class="py-3 text-left">Product</th>
                        <th class="py-3 text-right">Qty</th>
                        <th class="py-3 text-right">Price</th>
                        <th class="py-3 text-right">Total</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($sale->items as $item)

                        <tr class="border-b">

                            <td class="py-3">
                                {{ $item->product_name }}
                            </td>

                            <td class="py-3 text-right">
                                {{ $item->quantity }} {{ $item->unit_name }}
                            </td>

                            <td class="py-3 text-right">
                                ৳ {{ number_format($item->unit_price, 2) }}
                            </td>

                            <td class="py-3 text-right">
                                ৳ {{ number_format($item->subtotal, 2) }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        <div class="max-w-xs ml-auto mt-5 space-y-2 text-sm">

            <div class="flex justify-between">
                <span>Subtotal</span>
                <span>৳ {{ number_format($sale->subtotal, 2) }}</span>
            </div>

            <div class="flex justify-between">
                <span>Discount</span>
                <span>৳ {{ number_format($sale->discount, 2) }}</span>
            </div>

            <div class="flex justify-between font-bold text-lg border-t pt-3">
                <span>Total</span>
                <span>৳ {{ number_format($sale->total, 2) }}</span>
            </div>

            <div class="flex justify-between text-emerald-600">
                <span>Paid</span>
                <span>৳ {{ number_format($sale->paid_amount, 2) }}</span>
            </div>

            <div class="flex justify-between text-red-600 font-bold">
                <span>Due</span>
                <span>৳ {{ number_format($sale->due_amount, 2) }}</span>
            </div>

        </div>

        @if($sale->note)
            <div class="border-t mt-5 pt-3 text-sm">
                Note: {{ $sale->note }}
            </div>
        @endif

        <div class="text-center mt-8 border-t pt-5 text-sm text-gray-500">
            Thank you for your purchase.
        </div>

    </div>

</div>

<style>
@media print {
    body {
        background: white !important;
    }

    body * {
        visibility: hidden;
    }

    .max-w-3xl,
    .max-w-3xl * {
        visibility: visible;
    }

    .max-w-3xl {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        max-width: 100%;
    }

    .print\:hidden {
        display: none !important;
    }
}
</style>

@endsection
