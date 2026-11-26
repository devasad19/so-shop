
@extends('apps.shop_master')

@section('content')

<div class="max-w-7xl mx-auto space-y-4">

    <div class="flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">বিক্রির ইতিহাস</h1>
            <p class="text-gray-500 text-sm">Sales History</p>
        </div>

        <a href="{{ route('owner.sales.create') }}"
           class="bg-emerald-600 text-white px-4 py-3 rounded-xl font-semibold">
            + নতুন বিক্রি
        </a>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

        <div class="bg-white border rounded-2xl p-4">
            <div class="text-sm text-gray-500">মোট বিক্রি</div>
            <div class="text-xl font-bold text-emerald-600">
                ৳ {{ number_format($totalSales, 2) }}
            </div>
        </div>

        <div class="bg-white border rounded-2xl p-4">
            <div class="text-sm text-gray-500">মোট আদায়</div>
            <div class="text-xl font-bold text-blue-600">
                ৳ {{ number_format($totalPaid, 2) }}
            </div>
        </div>

        <div class="bg-white border rounded-2xl p-4">
            <div class="text-sm text-gray-500">মোট বাকি</div>
            <div class="text-xl font-bold text-red-600">
                ৳ {{ number_format($totalDue, 2) }}
            </div>
        </div>

    </div>

    {{-- Filters --}}
    <form method="GET"
          class="bg-white border rounded-2xl p-4 grid grid-cols-1 sm:grid-cols-4 gap-3">

        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Invoice / নাম / ফোন"
               class="border rounded-xl px-3 py-2">

        <input type="date"
               name="from"
               value="{{ request('from') }}"
               class="border rounded-xl px-3 py-2">

        <input type="date"
               name="to"
               value="{{ request('to') }}"
               class="border rounded-xl px-3 py-2">

        <div class="flex gap-2">
            <button class="bg-emerald-600 text-white rounded-xl px-4 py-2 flex-1">
                Search
            </button>

            <a href="{{ route('owner.sales.index') }}"
               class="bg-gray-100 rounded-xl px-4 py-2">
                Reset
            </a>
        </div>

    </form>

    {{-- Desktop --}}
    <div class="hidden md:block bg-white border rounded-2xl overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3 text-left">Invoice</th>
                    <th class="p-3 text-left">Customer</th>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-right">Total</th>
                    <th class="p-3 text-right">Paid</th>
                    <th class="p-3 text-right">Due</th>
                    <th class="p-3 text-center">Details</th>
                </tr>
            </thead>

            <tbody>

                @forelse($sales as $sale)

                    <tr class="border-t hover:bg-gray-50">

                        <td class="p-3 font-semibold">
                            {{ $sale->invoice_number }}
                        </td>

                        <td class="p-3">
                            {{ $sale->customer_name ?: 'Walk-in' }}
                            <div class="text-xs text-gray-400">
                                {{ $sale->customer_phone }}
                            </div>
                        </td>

                        <td class="p-3">
                            {{ $sale->created_at->format('d M Y, h:i A') }}
                        </td>

                        <td class="p-3 text-right">
                            ৳ {{ number_format($sale->total, 2) }}
                        </td>

                        <td class="p-3 text-right text-emerald-600">
                            ৳ {{ number_format($sale->paid_amount, 2) }}
                        </td>

                        <td class="p-3 text-right text-red-600">
                            ৳ {{ number_format($sale->due_amount, 2) }}
                        </td>

                        <td class="p-3 text-right">
                            <div class="flex gap-2"> 
                            <a href="{{ route('owner.sales.show', $sale) }}"
                               class="px-3 py-2 rounded-lg bg-blue-50 text-blue-600">
                                Details
                            </a>

                                    
            <a href="{{ route('owner.sales.invoice', $sale) }}"
               class="bg-emerald-600 text-white rounded-xl px-4 py-2">
                Invoice
            </a>
                                    </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="text-center py-10 text-gray-400">
                            কোনো বিক্রির তথ্য নেই।
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- Mobile --}}
    <div class="md:hidden space-y-3">

        @forelse($sales as $sale)

            <a href="{{ route('owner.sales.show', $sale) }}"
               class="block bg-white border rounded-2xl p-4 space-y-3">

                <div class="flex justify-between gap-2">

                    <div>
                        <div class="font-bold text-emerald-700">
                            {{ $sale->invoice_number }}
                        </div>

                        <div class="text-sm text-gray-500">
                            {{ $sale->customer_name ?: 'Walk-in' }}
                        </div>
                    </div>

                    <div class="text-right">
                        <div class="font-bold">
                            ৳ {{ number_format($sale->total, 2) }}
                        </div>

                        <div class="text-xs text-gray-400">
                            {{ $sale->created_at->format('d M, h:i A') }}
                        </div>
                    </div>

                </div>

                <div class="grid grid-cols-2 gap-2 text-sm">

                    <div class="bg-emerald-50 rounded-xl p-2">
                        <div class="text-gray-500">Paid</div>
                        <div class="font-bold text-emerald-700">
                            ৳ {{ number_format($sale->paid_amount, 2) }}
                        </div>
                    </div>

                    <div class="bg-red-50 rounded-xl p-2">
                        <div class="text-gray-500">Due</div>
                        <div class="font-bold text-red-600">
                            ৳ {{ number_format($sale->due_amount, 2) }}
                        </div>
                    </div>

                </div>

                <div class="text-blue-600 text-sm text-right">
                    বিস্তারিত দেখুন →
                </div>

            </a>

        @empty

            <div class="bg-white rounded-2xl p-8 text-center text-gray-400">
                কোনো বিক্রির তথ্য নেই।
            </div>

        @endforelse

    </div>

    <div>
        {{ $sales->links() }}
    </div>

</div>

@endsection
