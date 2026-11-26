
@extends('apps.shop_master')

@section('content')

<div class="max-w-4xl mx-auto space-y-4">

    <div class="flex flex-wrap justify-between gap-3 items-center">
        <div>
            <h1 class="text-2xl font-bold">বিক্রির বিস্তারিত</h1>
            <p class="text-sm text-gray-500">{{ $sale->invoice_number }}</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('owner.sales.invoice', $sale) }}"
               class="bg-emerald-600 text-white rounded-xl px-4 py-2">
                Invoice
            </a>

            <a href="{{ route('owner.sales.index') }}"
               class="bg-gray-100 rounded-xl px-4 py-2">
                Back
            </a>
        </div>
    </div>

    <div class="bg-white border rounded-2xl p-4 space-y-3">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

            <div>
                <div class="text-sm text-gray-500">Invoice</div>
                <div class="font-bold">{{ $sale->invoice_number }}</div>
            </div>

            <div>
                <div class="text-sm text-gray-500">Date</div>
                <div class="font-medium">
                    {{ $sale->created_at->format('d M Y, h:i A') }}
                </div>
            </div>

            <div>
                <div class="text-sm text-gray-500">Customer</div>
                <div>{{ $sale->customer_name ?: 'Walk-in Customer' }}</div>
            </div>

            <div>
                <div class="text-sm text-gray-500">Phone</div>
                <div>{{ $sale->customer_phone ?: '-' }}</div>
            </div>

        </div>

    </div>

    {{-- Products --}}
    <div class="bg-white border rounded-2xl overflow-hidden">

        <div class="p-4 font-bold border-b">
            বিক্রিত পণ্য
        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 text-left">Product</th>
                        <th class="p-3 text-right">Qty</th>
                        <th class="p-3 text-right">Price</th>
                        <th class="p-3 text-right">Total</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($sale->items as $item)

                        <tr class="border-t">

                            <td class="p-3">
                                {{ $item->product_name }}
                            </td>

                            <td class="p-3 text-right">
                                {{ $item->quantity }}
                                {{ $item->unit_name }}
                            </td>

                            <td class="p-3 text-right">
                                ৳ {{ number_format($item->unit_price, 2) }}
                            </td>

                            <td class="p-3 text-right font-semibold">
                                ৳ {{ number_format($item->subtotal, 2) }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

    {{-- Amounts --}}
    <div class="bg-white border rounded-2xl p-4 space-y-3">

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

        <div class="text-sm text-gray-500">
            Payment Method: {{ ucfirst($sale->payment_method) }}
        </div>

    </div>

    {{-- Payment history --}}
    <div class="bg-white border rounded-2xl p-4">

        <h2 class="font-bold mb-3">Payment History</h2>

        @forelse($sale->payments as $payment)

            <div class="border-t py-3 flex flex-wrap justify-between gap-2">

                <div>
                    <div class="font-semibold">
                        {{ ucfirst($payment->payment_method) }}
                    </div>

                    <div class="text-xs text-gray-500">
                        {{ $payment->created_at->format('d M Y, h:i A') }}
                    </div>

                    @if($payment->note)
                        <div class="text-xs text-gray-500">
                            {{ $payment->note }}
                        </div>
                    @endif
                </div>

                <div class="font-bold text-emerald-600">
                    ৳ {{ number_format($payment->amount, 2) }}
                </div>

            </div>

        @empty

            <p class="text-gray-400">Payment record নেই।</p>

        @endforelse

    </div>

    @if($sale->note)
        <div class="bg-white border rounded-2xl p-4">
            <div class="font-semibold mb-1">Note</div>
            {{ $sale->note }}
        </div>
    @endif

</div>

@endsection
