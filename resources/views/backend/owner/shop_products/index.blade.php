@extends('apps.shop_master')

@section('title', 'আমার পণ্য - OS Shop')
@section('page_heading', 'আমার পণ্য')

@section('content')
<div class="max-w-7xl mx-auto space-y-5">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">আমার পণ্য</h1>
            <p class="text-sm text-gray-500 mt-1">
                আপনার দোকানে যোগ করা সব পণ্য এখানে পরিচালনা করুন।
            </p>
        </div>

        <a href="{{ route('owner.shop-products.catalog') }}"
           class="inline-flex justify-center items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-3 rounded-xl font-semibold">
            + পণ্য যোগ করুন
        </a>
    </div>

    {{-- Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Total Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="bg-white border rounded-2xl p-4">
            <p class="text-sm text-gray-500">দোকানে মোট যোগ করা পণ্য</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">
                {{ number_format($totalShopProducts) }}
            </p>
        </div>

        <div class="bg-white border rounded-2xl p-4">
            <p class="text-sm text-gray-500">পণ্য ক্যাটাগরি</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">
                {{ $categories->count() }}
            </p>
        </div>
    </div>

    {{-- Category Wise Count --}}
    <div class="bg-white border rounded-2xl p-4 space-y-3">
        <div>
            <h2 class="font-bold text-gray-800">ক্যাটাগরি অনুযায়ী আমার পণ্য</h2>
            <p class="text-xs text-gray-500 mt-1">
                কোন ক্যাটাগরিতে আপনার দোকানে কতটি পণ্য যোগ করা হয়েছে
            </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($categories as $category)
                <div class="border rounded-xl p-3">
                    <p class="text-sm text-gray-600 truncate" title="{{ $category->name }}">
                        {{ $category->name }}
                    </p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ number_format($category->claimed_count) }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">টি পণ্য যোগ করা হয়েছে</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Search --}}
    <form method="GET"
          action="{{ route('owner.shop-products.index') }}"
          class="bg-white border rounded-2xl p-4 flex flex-col sm:flex-row gap-3">

        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="পণ্যের নাম, SKU বা Barcode দিয়ে খুঁজুন"
               class="flex-1 min-w-0 border rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500">

        <button type="submit"
                class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-3 rounded-xl font-medium">
            খুঁজুন
        </button>

        <a href="{{ route('owner.shop-products.index') }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-3 rounded-xl text-center">
            Reset
        </a>
    </form>

    {{-- Product Table --}}
    <div class="bg-white border rounded-2xl overflow-hidden">

        <div class="p-4 border-b flex flex-wrap items-center justify-between gap-2">
            <div>
                <h2 class="font-bold text-gray-800">আমার পণ্যের তালিকা</h2>
                <p class="text-xs text-gray-500 mt-1">
                    মোট {{ number_format($shopProducts->total()) }} টি পণ্য
                </p>
            </div>
            <span class="text-xs bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-full">
                {{ $shopProducts->count() }} টি দেখানো হচ্ছে
            </span>
        </div>

        {{-- Desktop --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-4 py-4 text-left w-14">#</th>
                        <th class="px-4 py-4 text-left">পণ্য</th>
                        <th class="px-4 py-4 text-left">ক্যাটাগরি</th>
                        <th class="px-4 py-4 text-left">বর্তমান স্টক</th>
                        <th class="px-4 py-4 text-left">ক্রয়মূল্য</th>
                        <th class="px-4 py-4 text-left">বিক্রয়মূল্য</th>
                        <th class="px-4 py-4 text-left">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($shopProducts as $shopProduct)
                        @php
                            $product = $shopProduct->product;
                            $imagePath = $product?->image ? ltrim($product->image, '/') : null;
                            $imageUrl = $imagePath
                                ? (str_starts_with($imagePath, 'http')
                                    ? $imagePath
                                    : (file_exists(public_path($imagePath))
                                        ? asset($imagePath)
                                        : asset('storage/' . $imagePath)))
                                : null;
                        @endphp

                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 text-gray-400 font-medium">
                                {{ $shopProducts->firstItem() + $loop->index }}
                            </td>

                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3 min-w-56">
                                    @if($imageUrl)
                                        <img src="{{ $imageUrl }}"
                                             alt="{{ $product?->name }}"
                                             loading="lazy"
                                             class="w-14 h-14 object-contain rounded-xl border bg-white p-1"
                                             onerror="this.style.display='none'">
                                    @else
                                        <div class="w-14 h-14 rounded-xl bg-gray-100 flex items-center justify-center text-2xl">
                                            📦
                                        </div>
                                    @endif

                                    <div>
                                        <p class="font-semibold text-gray-800">
                                            {{ $product?->name ?? 'পণ্য পাওয়া যায়নি' }}
                                        </p>
                                        @if($product?->sku)
                                            <p class="text-xs text-gray-400 mt-1">
                                                SKU: {{ $product->sku }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-4 text-gray-600">
                                {{ $product?->category?->name ?? '—' }}
                            </td>

                            <td class="px-4 py-4">
                                <span class="font-semibold text-gray-800">
                                    {{ number_format((float) $shopProduct->current_stock, 2) }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $product?->unit?->short_name ?? '' }}
                                </span>
                            </td>

                            <td class="px-4 py-4 text-gray-700">
                                ৳{{ number_format((float) $shopProduct->purchase_price, 2) }}
                            </td>

                            <td class="px-4 py-4 font-semibold text-gray-800">
                                ৳{{ number_format((float) $shopProduct->selling_price, 2) }}
                            </td>

                            <td class="px-4 py-4">
                                <div class="flex flex-wrap gap-2">

                                    <a href="{{ route('owner.shop-products.purchase', $shopProduct) }}"
                                       class="bg-blue-50 text-blue-700 hover:bg-blue-100 px-3 py-2 rounded-lg">
                                        ক্রয়
                                    </a>

                                    <button type="button"
                                            disabled
                                            title="POS তৈরি হলে চালু হবে"
                                            class="bg-gray-100 text-gray-400 px-3 py-2 rounded-lg cursor-not-allowed">
                                        বিক্রি
                                    </button>

                                    <a href="{{ route('owner.shop-products.show', $shopProduct) }}"
                                       class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-3 py-2 rounded-lg">
                                        Details
                                    </a>

                                    <form action="{{ route('owner.shop-products.destroy', $shopProduct) }}"
                                          method="POST"
                                          onsubmit="return confirm('আপনি কি এই পণ্যটি Shop List থেকে Remove করতে চান?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="bg-red-50 text-red-600 hover:bg-red-100 px-3 py-2 rounded-lg">
                                            Remove
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-14 text-gray-400">
                                <div class="text-3xl mb-2">📦</div>
                                আপনার Shop List-এ কোনো পণ্য নেই।
                                <div class="mt-3">
                                    <a href="{{ route('owner.shop-products.catalog') }}"
                                       class="text-emerald-600 font-semibold hover:underline">
                                        Catalog থেকে পণ্য যোগ করুন
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="md:hidden divide-y">
            @forelse($shopProducts as $shopProduct)
                @php
                    $product = $shopProduct->product;
                    $imagePath = $product?->image ? ltrim($product->image, '/') : null;
                    $imageUrl = $imagePath
                        ? (str_starts_with($imagePath, 'http')
                            ? $imagePath
                            : (file_exists(public_path($imagePath))
                                ? asset($imagePath)
                                : asset('storage/' . $imagePath)))
                        : null;
                @endphp

                <div class="p-4 space-y-3">
                    <div class="flex gap-3">
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}"
                                 alt="{{ $product?->name }}"
                                 loading="lazy"
                                 class="w-20 h-20 object-contain rounded-xl border bg-white p-1"
                                 onerror="this.style.display='none'">
                        @else
                            <div class="w-20 h-20 rounded-xl bg-gray-100 flex items-center justify-center text-2xl">
                                📦
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between gap-2">
                                <h3 class="font-semibold text-gray-800">
                                    {{ $product?->name ?? 'পণ্য পাওয়া যায়নি' }}
                                </h3>
                                <span class="text-xs text-gray-400 whitespace-nowrap">
                                    #{{ $shopProducts->firstItem() + $loop->index }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-500 mt-1">
                                {{ $product?->category?->name ?? '—' }}
                            </p>

                            @if($product?->sku)
                                <p class="text-xs text-gray-400 mt-1">
                                    SKU: {{ $product->sku }}
                                </p>
                            @endif

                            <span class="inline-flex mt-2 bg-gray-100 text-gray-600 text-xs px-2.5 py-1 rounded-lg">
                                একক: {{ $product?->unit?->short_name ?? '—' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-xs text-gray-500">বর্তমান স্টক</p>
                            <p class="font-bold text-gray-800 mt-1">
                                {{ number_format((float) $shopProduct->current_stock, 2) }}
                                {{ $product?->unit?->short_name ?? '' }}
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-xs text-gray-500">ক্রয়মূল্য</p>
                            <p class="font-bold text-gray-800 mt-1">
                                ৳{{ number_format((float) $shopProduct->purchase_price, 2) }}
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-3 col-span-2">
                            <p class="text-xs text-gray-500">বিক্রয়মূল্য</p>
                            <p class="font-bold text-emerald-700 mt-1">
                                ৳{{ number_format((float) $shopProduct->selling_price, 2) }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('owner.shop-products.purchase', $shopProduct) }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl py-3 text-center font-medium">
                            ক্রয়
                        </a>

                        <button type="button"
                                disabled
                                class="bg-gray-100 text-gray-400 rounded-xl py-3 cursor-not-allowed">
                            বিক্রি
                        </button>

                        <a href="{{ route('owner.shop-products.show', $shopProduct) }}"
                           class="bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl py-3 text-center">
                            Details
                        </a>

                        <form action="{{ route('owner.shop-products.destroy', $shopProduct) }}"
                              method="POST"
                              onsubmit="return confirm('পণ্যটি Remove করবেন?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="w-full bg-red-50 hover:bg-red-100 text-red-600 rounded-xl py-3">
                                Remove
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-gray-400">
                    আপনার Shop List-এ কোনো পণ্য নেই।
                    <div class="mt-3">
                        <a href="{{ route('owner.shop-products.catalog') }}"
                           class="text-emerald-600 font-semibold">
                            Catalog থেকে পণ্য যোগ করুন
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="p-4 border-t">
            {{ $shopProducts->links() }}
        </div>
    </div>
</div>
@endsection