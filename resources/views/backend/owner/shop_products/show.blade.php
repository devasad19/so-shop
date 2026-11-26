@extends('apps.shop_master')

@section('title', 'পণ্য - OS Shop')
@section('page_heading', 'পণ্য')

@section('content')

@section('content')

<div class="max-w-5xl mx-auto space-y-5">

    <a href="{{ route('owner.shop-products.index') }}"
       class="text-emerald-600 hover:text-emerald-700 cursor-pointer">
        ← আমার পণ্য
    </a>

    <div class="bg-white border rounded-2xl p-5">

        <div class="flex flex-col sm:flex-row gap-5">

            @if($shopProduct->product?->image)
                <img src="{{ asset('storage/'.$shopProduct->product->image) }}"
                     class="w-32 h-32 object-cover rounded-2xl">
            @else
                <div class="w-32 h-32 bg-gray-100 rounded-2xl flex items-center justify-center">
                    📦
                </div>
            @endif

            <div class="flex-1 space-y-2">

                <h1 class="text-2xl font-bold text-gray-800">
                    {{ $shopProduct->product?->name }}
                </h1>

                <p class="text-gray-500">
                    Category: {{ $shopProduct->product?->category?->name }}
                </p>

                <p class="text-gray-500">
                    Unit: {{ $shopProduct->product?->unit?->name }}
                </p>

                <p class="text-gray-500">
                    SKU: {{ $shopProduct->product?->sku ?: '—' }}
                </p>

                <p class="text-gray-500">
                    Barcode: {{ $shopProduct->product?->barcode ?: '—' }}
                </p>

            </div>

        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">

            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-500">বর্তমান স্টক</p>
                <p class="text-xl font-bold mt-2">
                    {{ $shopProduct->current_stock }}
                </p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-500">ক্রয়মূল্য</p>
                <p class="text-xl font-bold mt-2">
                    ৳{{ number_format((float)$shopProduct->purchase_price, 2) }}
                </p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-500">বিক্রয়মূল্য</p>
                <p class="text-xl font-bold mt-2">
                    ৳{{ number_format((float)$shopProduct->selling_price, 2) }}
                </p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-500">Stock Alert</p>
                <p class="text-xl font-bold mt-2">
                    {{ $shopProduct->alert_quantity }}
                </p>
            </div>

        </div>

        <div class="flex flex-wrap gap-3 mt-6">

            <a href="{{ route('owner.shop-products.purchase', $shopProduct) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl cursor-pointer">
                ক্রয়
            </a>

            <a href="{{ route('owner.shop-products.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-3 rounded-xl cursor-pointer">
                ফিরে যান
            </a>

        </div>

    </div>

    {{-- Purchase History --}}
    <div class="bg-white border rounded-2xl overflow-hidden">

        <div class="p-5 border-b">
            <h2 class="font-bold text-gray-800">
                ক্রয়ের ইতিহাস
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left">তারিখ</th>
                        <th class="px-4 py-3 text-left">প্যাক</th>
                        <th class="px-4 py-3 text-left">মোট Unit</th>
                        <th class="px-4 py-3 text-left">প্রতি প্যাক</th>
                        <th class="px-4 py-3 text-left">মোট</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($shopProduct->purchases->sortByDesc('id') as $purchase)

                        <tr>

                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ $purchase->created_at->format('d M Y, h:i A') }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $purchase->pack_quantity }}
                                × {{ $purchase->pack_size }}
                                {{ $shopProduct->product?->unit?->short_name }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $purchase->total_quantity }}
                            </td>

                            <td class="px-4 py-3">
                                ৳{{ number_format((float)$purchase->price_per_pack, 2) }}
                            </td>

                            <td class="px-4 py-3 font-semibold">
                                ৳{{ number_format((float)$purchase->total_amount, 2) }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center py-10 text-gray-400">
                                এখনো কোনো ক্রয় করা হয়নি।
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- Stock History --}}
    <div class="bg-white border rounded-2xl overflow-hidden">

        <div class="p-5 border-b">
            <h2 class="font-bold text-gray-800">
                Stock History
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left">তারিখ</th>
                        <th class="px-4 py-3 text-left">ধরন</th>
                        <th class="px-4 py-3 text-left">পরিমাণ</th>
                        <th class="px-4 py-3 text-left">আগের স্টক</th>
                        <th class="px-4 py-3 text-left">পরের স্টক</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($shopProduct->stockMovements->sortByDesc('id') as $movement)

                        <tr>

                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ $movement->created_at->format('d M Y, h:i A') }}
                            </td>

                            <td class="px-4 py-3">
                                {{ ucfirst(str_replace('_', ' ', $movement->type)) }}
                            </td>

                            <td class="px-4 py-3 font-semibold
                                {{ $movement->direction == 'in' ? 'text-green-600' : 'text-red-600' }}">

                                {{ $movement->direction == 'in' ? '+' : '-' }}{{ $movement->quantity }}

                            </td>

                            <td class="px-4 py-3">
                                {{ $movement->stock_before }}
                            </td>

                            <td class="px-4 py-3 font-semibold">
                                {{ $movement->stock_after }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center py-10 text-gray-400">
                                কোনো Stock History নেই।
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection