@extends('apps.shop_master')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Stock Movement
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                পণ্যের স্টক আসা-যাওয়ার সম্পূর্ণ ইতিহাস
            </p>
        </div>
    </div>

    {{-- Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Stock Adjustment Form --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5">

        <div class="mb-5">
            <h2 class="text-lg font-bold text-gray-800">
                স্টক সংশোধন
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                বাস্তব স্টক গণনার সঙ্গে সফটওয়্যারের স্টকের পার্থক্য থাকলে সংশোধন করুন।
            </p>
        </div>

        <form action="{{ route('owner.stock-movements.adjustment') }}"
              method="POST"
              class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">

            @csrf

            {{-- Product --}}
            <div class="xl:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    পণ্য নির্বাচন
                </label>

                <select name="product_id"
                        required
                        class="w-full border border-gray-300 rounded-xl px-3 py-3 focus:ring-2 focus:ring-emerald-500 outline-none">

                    <option value="">পণ্য নির্বাচন করুন</option>

                    @foreach($products as $product)
                        <option value="{{ $product->id }}"
                            {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                            — বর্তমান স্টক: {{ $product->current_stock }}
                            @if($product->sku)
                                ({{ $product->sku }})
                            @endif
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- Direction --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    পরিবর্তনের ধরন
                </label>

                <select name="direction"
                        required
                        class="w-full border border-gray-300 rounded-xl px-3 py-3 focus:ring-2 focus:ring-emerald-500 outline-none">

                    <option value="">নির্বাচন করুন</option>
                    <option value="in" {{ old('direction') == 'in' ? 'selected' : '' }}>
                        স্টক বৃদ্ধি (+)
                    </option>
                    <option value="out" {{ old('direction') == 'out' ? 'selected' : '' }}>
                        স্টক হ্রাস (-)
                    </option>

                </select>
            </div>

            {{-- Quantity --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    পরিমাণ
                </label>

                <input type="number"
                       name="quantity"
                       step="0.001"
                       min="0.001"
                       value="{{ old('quantity') }}"
                       required
                       placeholder="যেমন: 5"
                       class="w-full border border-gray-300 rounded-xl px-3 py-3 focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>

            {{-- Unit Cost --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    প্রতি ইউনিট ক্রয়মূল্য
                </label>

                <input type="number"
                       name="unit_cost"
                       step="0.01"
                       min="0"
                       value="{{ old('unit_cost', 0) }}"
                       placeholder="0.00"
                       class="w-full border border-gray-300 rounded-xl px-3 py-3 focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>

            {{-- Note --}}
            <div class="md:col-span-2 xl:col-span-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    পরিবর্তনের কারণ
                </label>

                <input type="text"
                       name="note"
                       value="{{ old('note') }}"
                       required
                       maxlength="500"
                       placeholder="যেমন: বাস্তবে ২ কেজি চাল নষ্ট পাওয়া গেছে"
                       class="w-full border border-gray-300 rounded-xl px-3 py-3 focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>

            {{-- Submit --}}
            <div class="flex items-end">
                <button type="submit"
                        onclick="return confirm('আপনি কি স্টক সংশোধন করতে চান?')"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-5 py-3 rounded-xl transition cursor-pointer">

                    স্টক সংশোধন করুন

                </button>
            </div>

        </form>
    </div>

    {{-- Search & Filter --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-4">

        <form method="GET"
              action="{{ route('owner.stock-movements.index') }}"
              class="grid grid-cols-1 md:grid-cols-3 gap-3">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="পণ্যের নাম, SKU বা Barcode দিয়ে খুঁজুন"
                   class="w-full border border-gray-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500">

            <select name="type"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500">

                <option value="">সব ধরনের Movement</option>
                <option value="opening" @selected(request('type') == 'opening')>Opening Stock</option>
                <option value="purchase" @selected(request('type') == 'purchase')>Purchase</option>
                <option value="sale" @selected(request('type') == 'sale')>Sale</option>
                <option value="sale_return" @selected(request('type') == 'sale_return')>Sale Return</option>
                <option value="purchase_return" @selected(request('type') == 'purchase_return')>Purchase Return</option>
                <option value="adjustment" @selected(request('type') == 'adjustment')>Adjustment</option>

            </select>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 bg-gray-800 hover:bg-gray-900 text-white rounded-xl px-4 py-3 cursor-pointer">
                    খুঁজুন
                </button>

                <a href="{{ route('owner.stock-movements.index') }}"
                   class="px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-700 cursor-pointer">
                    Reset
                </a>
            </div>

        </form>
    </div>

    {{-- History --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">

        <div class="p-5 border-b border-gray-100">
            <h2 class="font-bold text-gray-800">
                স্টক পরিবর্তনের ইতিহাস
            </h2>
        </div>

@php
    $typeLabels = [
        'opening' => 'Opening',
        'purchase' => 'Purchase',
        'sale' => 'Sale',
        'sale_return' => 'Sale Return',
        'purchase_return' => 'Purchase Return',
        'adjustment' => 'Adjustment',
    ];
@endphp


        {{-- Desktop Table --}}
        <div class="hidden md:block overflow-x-auto">

            <table class="w-full text-sm text-left">

                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-5 py-4">তারিখ</th>
                        <th class="px-5 py-4">পণ্য</th>
                        <th class="px-5 py-4">ধরন</th>
                        <th class="px-5 py-4">পরিমাণ</th>
                        <th class="px-5 py-4">আগের স্টক</th>
                        <th class="px-5 py-4">পরের স্টক</th>
                        <th class="px-5 py-4">পরিবর্তনকারী</th>
                        <th class="px-5 py-4">কারণ</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($movements as $movement)

                        <tr class="hover:bg-gray-50">

                            <td class="px-5 py-4 whitespace-nowrap text-gray-600">
                                {{ $movement->created_at->format('d M Y, h:i A') }}
                            </td>

                            <td class="px-5 py-4 font-medium text-gray-800">
                                {{ $movement->product?->name ?? 'Deleted Product' }}
                            </td>

                            <td class="px-5 py-4">
                        

                                <span class="px-2 py-1 rounded-lg text-xs bg-gray-100 text-gray-700">
                                    {{ $typeLabels[$movement->type] ?? $movement->type }}
                                </span>
                            </td>

                            <td class="px-5 py-4 font-semibold whitespace-nowrap
                                {{ $movement->direction == 'in' ? 'text-green-600' : 'text-red-600' }}">

                                {{ $movement->direction == 'in' ? '+' : '-' }}{{ $movement->quantity }}

                            </td>

                            <td class="px-5 py-4 text-gray-600">
                                {{ $movement->stock_before }}
                            </td>

                            <td class="px-5 py-4 font-semibold text-gray-800">
                                {{ $movement->stock_after }}
                            </td>

                            <td class="px-5 py-4 text-gray-600">
                                {{ $movement->creator?->name ?? 'System' }}
                            </td>

                            <td class="px-5 py-4 text-gray-500 max-w-xs">
                                {{ $movement->note ?: '—' }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                                এখনো কোনো Stock Movement পাওয়া যায়নি।
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Mobile Cards --}}
        <div class="md:hidden divide-y divide-gray-100">

            @forelse($movements as $movement)

                <div class="p-4 space-y-3">

                    <div class="flex justify-between gap-3">
                        <div>
                            <h3 class="font-semibold text-gray-800">
                                {{ $movement->product?->name ?? 'Deleted Product' }}
                            </h3>

                            <p class="text-xs text-gray-400 mt-1">
                                {{ $movement->created_at->format('d M Y, h:i A') }}
                            </p>
                        </div>

                        <span class="font-bold whitespace-nowrap
                            {{ $movement->direction == 'in' ? 'text-green-600' : 'text-red-600' }}">

                            {{ $movement->direction == 'in' ? '+' : '-' }}{{ $movement->quantity }}

                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-sm">

                        <div>
                            <p class="text-xs text-gray-400">Movement Type</p>
                            <p class="text-gray-700">
                                {{ $typeLabels[$movement->type] ?? $movement->type }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">আগের স্টক</p>
                            <p class="text-gray-700">{{ $movement->stock_before }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">পরের স্টক</p>
                            <p class="font-semibold text-gray-800">{{ $movement->stock_after }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">পরিবর্তনকারী</p>
                            <p class="text-gray-700">{{ $movement->creator?->name ?? 'System' }}</p>
                        </div>

                    </div>

                    @if($movement->note)
                        <p class="text-sm text-gray-500 bg-gray-50 rounded-lg p-3">
                            {{ $movement->note }}
                        </p>
                    @endif

                </div>

            @empty

                <div class="p-10 text-center text-gray-400">
                    এখনো কোনো Stock Movement পাওয়া যায়নি।
                </div>

            @endforelse

        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $movements->links() }}
        </div>

    </div>

</div>

@endsection