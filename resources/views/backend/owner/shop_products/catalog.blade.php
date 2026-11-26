@extends('apps.shop_master')

@section('title', 'Product Catalog - OS Shop')
@section('page_heading', 'Product Catalog')

@section('content')
<div class="max-w-7xl mx-auto space-y-5">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Product Catalog</h1>
            <p class="text-sm text-gray-500 mt-1">
                Catalog থেকে পণ্য নির্বাচন করে আপনার দোকানে যোগ করুন।
            </p>
        </div>

        <a href="{{ route('owner.shop-products.index') }}"
           class="inline-flex justify-center items-center gap-2 bg-gray-800 hover:bg-gray-900 text-white px-5 py-3 rounded-xl font-semibold">
            <span>আমার পণ্য দেখুন</span>
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

    {{-- Overall Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="bg-white border rounded-2xl p-4">
            <p class="text-sm text-gray-500">Catalog-এ মোট সক্রিয় পণ্য</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">
                {{ number_format($totalCatalogProducts) }}
            </p>
        </div>

        <div class="bg-white border rounded-2xl p-4">
            <p class="text-sm text-gray-500">আমার দোকানে যোগ হয়েছে</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">
                {{ number_format($totalClaimed) }}
            </p>
        </div>

        <div class="bg-white border rounded-2xl p-4">
            <p class="text-sm text-gray-500">এখনো যোগ করা হয়নি</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">
                {{ number_format($totalAvailable) }}
            </p>
        </div>
    </div>

    {{-- Category Summary --}}
    <div class="bg-white border rounded-2xl p-4 space-y-3">
        <div class="flex flex-wrap justify-between items-center gap-2">
            <div>
                <h2 class="font-bold text-gray-800">ক্যাটাগরি অনুযায়ী পণ্যের হিসাব</h2>
                <p class="text-xs text-gray-500 mt-1">প্রতিটি ক্যাটাগরিতে মোট ও যোগ করা পণ্যের সংখ্যা</p>
            </div>
            <span class="text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-full">
                {{ $categories->count() }} ক্যাটাগরি
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($categories as $category)
                <div class="border rounded-xl p-3">
                    <div class="flex justify-between items-start gap-2">
                        <p class="font-semibold text-gray-700">{{ $category->name }}</p>
                        <span class="text-xs text-gray-500">
                            মোট {{ number_format($category->total_products) }}
                        </span>
                    </div>

                    <div class="flex justify-between text-xs mt-3">
                        <span class="text-emerald-700">
                            যোগ হয়েছে: {{ number_format($category->claimed_count) }}
                        </span>
                        <span class="text-blue-700">
                            বাকি: {{ number_format($category->available_count) }}
                        </span>
                    </div>

                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden mt-2">
                        @php
                            $percent = $category->total_products > 0
                                ? min(100, ($category->claimed_count / $category->total_products) * 100)
                                : 0;
                        @endphp
                        <div class="h-full bg-emerald-500 rounded-full"
                             style="width: {{ $percent }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Search --}}
    <form method="GET"
          action="{{ route('owner.shop-products.catalog') }}"
          class="bg-white border rounded-2xl p-4 grid grid-cols-1 sm:grid-cols-3 gap-3">

        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="পণ্যের নাম, SKU বা Barcode লিখুন"
               class="sm:col-span-2 w-full border rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500">

        <select name="category_id"
                class="w-full border rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="">সব ক্যাটাগরি</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    @selected(request('category_id') == $category->id)>
                    {{ $category->name }} ({{ $category->total_products }})
                </option>
            @endforeach
        </select>

        <div class="sm:col-span-3 flex flex-wrap gap-2">
            <button type="submit"
                    class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2.5 rounded-xl font-medium">
                খুঁজুন
            </button>

            <a href="{{ route('owner.shop-products.catalog') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl">
                Reset
            </a>
        </div>
    </form>

    {{-- Product Selection --}}
    <form method="POST"
          action="{{ route('owner.shop-products.claim') }}"
          x-data="{
              selected: [],
              allIds: {{ Js::from($products->pluck('id')->map(fn($id) => (string) $id)->values()) }},
              toggleAll(checked) {
                  this.selected = checked ? [...this.allIds] : [];
              }
          }">
        @csrf

        <div class="bg-white border rounded-2xl overflow-hidden">

            <div class="p-4 border-b flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="font-bold text-gray-800">পণ্যের তালিকা</h2>
                    <p class="text-xs text-gray-500 mt-1">
                        এই পেজে {{ $products->count() }} টি পণ্য দেখানো হচ্ছে
                    </p>
                </div>

                <button type="submit"
                        :disabled="selected.length === 0"
                        class="bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white px-5 py-3 rounded-xl font-semibold">
                    <span x-text="selected.length + ' টি পণ্য দোকানে যোগ করুন'"></span>
                </button>
            </div>

            <div class="px-4 py-3 bg-gray-50 border-b">
                <label class="inline-flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                    <input type="checkbox"
                           @change="toggleAll($event.target.checked)"
                           :checked="allIds.length > 0 && selected.length === allIds.length"
                           class="rounded border-gray-300 text-emerald-600">
                    এই পেজের সব পণ্য নির্বাচন করুন
                </label>
            </div>

            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500">
                        <tr>
                            <th class="px-4 py-3 text-left w-12">#</th>
                            <th class="px-4 py-3 text-left">নির্বাচন</th>
                            <th class="px-4 py-3 text-left">পণ্য</th>
                            <th class="px-4 py-3 text-left">ক্যাটাগরি</th>
                            <th class="px-4 py-3 text-left">একক</th>
                            <th class="px-4 py-3 text-left">Barcode</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($products as $product)
                            @php
                                $imagePath = $product->image ? ltrim($product->image, '/') : null;
                                $imageUrl = $imagePath
                                    ? (str_starts_with($imagePath, 'http')
                                        ? $imagePath
                                        : (file_exists(public_path($imagePath))
                                            ? asset($imagePath)
                                            : asset('storage/' . $imagePath)))
                                    : null;
                            @endphp

                            <tr class="hover:bg-emerald-50/40">
                                <td class="px-4 py-3 text-gray-400 font-medium">
                                    {{ $products->firstItem() + $loop->index }}
                                </td>

                                <td class="px-4 py-3">
                                    <input type="checkbox"
                                           name="product_ids[]"
                                           value="{{ $product->id }}"
                                           x-model="selected"
                                           class="rounded border-gray-300 text-emerald-600">
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3 min-w-56">
                                        @if($imageUrl)
                                            <img src="{{ $imageUrl }}"
                                                 alt="{{ $product->name }}"
                                                 loading="lazy"
                                                 class="w-14 h-14 object-contain rounded-xl border bg-white p-1"
                                                 onerror="this.style.display='none'">
                                        @else
                                            <div class="w-14 h-14 rounded-xl bg-gray-100 flex items-center justify-center text-2xl">📦</div>
                                        @endif

                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                                            @if($product->sku)
                                                <p class="text-xs text-gray-400 mt-1">SKU: {{ $product->sku }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $product->category?->name ?? '—' }}
                                </td>

                                <td class="px-4 py-3">
                                    <span class="inline-flex bg-gray-100 text-gray-700 px-2.5 py-1 rounded-lg font-medium">
                                        {{ $product->unit?->short_name ?? '—' }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-gray-500">
                                    {{ $product->barcode ?: '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-14 text-gray-400">
                                    <div class="text-3xl mb-2">📦</div>
                                    এই অনুসন্ধানে নতুন কোনো পণ্য পাওয়া যায়নি।
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="md:hidden divide-y">
                @forelse($products as $product)
                    @php
                        $imagePath = $product->image ? ltrim($product->image, '/') : null;
                        $imageUrl = $imagePath
                            ? (str_starts_with($imagePath, 'http')
                                ? $imagePath
                                : (file_exists(public_path($imagePath))
                                    ? asset($imagePath)
                                    : asset('storage/' . $imagePath)))
                            : null;
                    @endphp

                    <label class="flex gap-3 p-4 hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox"
                               name="product_ids[]"
                               value="{{ $product->id }}"
                               x-model="selected"
                               class="mt-1 rounded border-gray-300 text-emerald-600">

                        @if($imageUrl)
                            <img src="{{ $imageUrl }}"
                                 alt="{{ $product->name }}"
                                 loading="lazy"
                                 class="w-20 h-20 object-contain rounded-xl border bg-white p-1"
                                 onerror="this.style.display='none'">
                        @else
                            <div class="w-20 h-20 rounded-xl bg-gray-100 flex items-center justify-center text-2xl">📦</div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between gap-2">
                                <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                                <span class="text-xs text-gray-400">#{{ $products->firstItem() + $loop->index }}</span>
                            </div>

                            <p class="text-sm text-gray-500 mt-1">
                                {{ $product->category?->name ?? 'ক্যাটাগরি নেই' }}
                            </p>

                            <div class="flex flex-wrap gap-2 mt-2 text-xs">
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded">
                                    একক: {{ $product->unit?->short_name ?? '—' }}
                                </span>
                                @if($product->sku)
                                    <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded">
                                        SKU: {{ $product->sku }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </label>
                @empty
                    <div class="p-10 text-center text-gray-400">
                        নতুন কোনো পণ্য পাওয়া যায়নি।
                    </div>
                @endforelse
            </div>

            <div class="p-4 border-t">
                {{ $products->links() }}
            </div>
        </div>
    </form>
</div>
@endsection