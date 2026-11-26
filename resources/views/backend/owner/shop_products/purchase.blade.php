@extends('apps.shop_master')

@section('title', 'পণ্য ক্রয় - OS Shop')
@section('page_heading', 'পণ্য ক্রয়')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">

    {{-- Header --}}
    <div>
        <a href="{{ route('owner.shop-products.index') }}"
           class="inline-flex text-sm text-emerald-600 hover:text-emerald-700">
            ← আমার পণ্য
        </a>

        <h1 class="text-2xl font-bold text-gray-800 mt-3">পণ্য ক্রয়</h1>
        <p class="text-sm text-gray-500 mt-1">
            নতুন পণ্য ক্রয় করে আপনার দোকানের স্টক বাড়ান।
        </p>
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

    {{-- Product Information --}}
    <div class="bg-white border rounded-2xl p-4 sm:p-5">
        <div class="flex items-center gap-4">

            @if($shopProduct->product?->image)
                @php
                    $imagePath = ltrim($shopProduct->product->image, '/');
                    $imageUrl = str_starts_with($imagePath, 'http')
                        ? $imagePath
                        : (file_exists(public_path($imagePath))
                            ? asset($imagePath)
                            : asset('storage/'.$imagePath));
                @endphp

                <img src="{{ $imageUrl }}"
                     alt="{{ $shopProduct->product?->name }}"
                     class="w-20 h-20 sm:w-24 sm:h-24 object-contain rounded-xl border bg-white p-1"
                     onerror="this.style.display='none'">
            @else
                <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-xl flex items-center justify-center text-3xl">
                    📦
                </div>
            @endif

            <div class="flex-1 min-w-0">
                <h2 class="text-lg font-bold text-gray-800">
                    {{ $shopProduct->product?->name }}
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    {{ $shopProduct->product?->category?->name }}
                </p>

                <span class="inline-flex mt-2 bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full">
                    Unit: {{ $shopProduct->product?->unit?->short_name }}
                </span>

                <p class="text-sm text-gray-500 mt-2">
                    বর্তমান স্টক:
                    <span class="font-bold text-emerald-700">
                        {{ number_format((float)$shopProduct->current_stock, 3) }}
                        {{ $shopProduct->product?->unit?->short_name }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    {{-- Purchase Form --}}
    <div class="bg-white border rounded-2xl p-4 sm:p-6">

        <form method="POST"
              action="{{ route('owner.shop-products.purchase.store', $shopProduct) }}"
              x-data="{
                  packSize: 1,
                  packQuantity: 1,
                  purchasePrice: 0,
                  sellingPrice: {{ (float)$shopProduct->selling_price }},
                  get totalQuantity() {
                      return (Number(this.packSize) || 0) * (Number(this.packQuantity) || 0);
                  },
                  get totalAmount() {
                      return this.totalQuantity * (Number(this.purchasePrice) || 0);
                  }
              }">

            @csrf

            <div class="space-y-5">

                {{-- Quantity and Package --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            পণ্যের পরিমাণ
                        </label>

                        <input type="number"
                               name="pack_quantity"
                               min="0.001"
                               step="0.001"
                               value="{{ old('pack_quantity', 1) }}"
                               x-model.number="packQuantity"
                               required
                               class="w-full border rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            প্রতি {{ $shopProduct->product?->unit?->short_name }} পরিমাণ
                        </label>

                        <select name="pack_size"
                                x-model.number="packSize"
                                required
                                class="w-full border rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500">

                            <option value="1">{{ $shopProduct->product?->unit?->short_name }}(খোলা)</option>
                            <option value="10">১০ {{ $shopProduct->product?->unit?->short_name }} (বস্তা)</option>
                            <option value="25">২৫ {{ $shopProduct->product?->unit?->short_name }} (বস্তা)</option>
                            <option value="50">৫০ {{ $shopProduct->product?->unit?->short_name }} (বস্তা)</option>
                            <option value="100">১০০ {{ $shopProduct->product?->unit?->short_name }} (বস্তা)</option>
                        </select>

                        <p class="text-xs text-gray-400 mt-1">
                            প্রতি প্যাকের মূল Unit পরিমাণ
                        </p>
                    </div>
                </div>

                {{-- Purchase and Selling Prices --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            প্রতি {{ $shopProduct->product?->unit?->short_name }} ক্রয়মূল্য (৳)
                        </label>

                        <input type="number"
                               name="purchase_price"
                               min="0"
                               step="0.01"
                               value="{{ old('purchase_price', $shopProduct->purchase_price) }}"
                               x-model.number="purchasePrice"
                               required
                               class="w-full border rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500">

                        <p class="text-xs text-gray-400 mt-1">
                            প্রতি মূল Unit-এর ক্রয়মূল্য
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            প্রতি {{ $shopProduct->product?->unit?->short_name }} বিক্রয়মূল্য (৳)
                        </label>

                        <input type="number"
                               name="selling_price"
                               min="0"
                               step="0.01"
                               value="{{ old('selling_price', $shopProduct->selling_price) }}"
                               x-model.number="sellingPrice"
                               required
                               class="w-full border rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500">

                        <p class="text-xs text-gray-400 mt-1">
                            ক্রয়ের সময় বিক্রয়মূল্য পরিবর্তন করতে পারবেন
                        </p>
                    </div>
                </div>

                {{-- Supplier --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        সাপ্লায়ার <span class="text-gray-400 font-normal">(ঐচ্ছিক)</span>
                    </label>

                    <select name="supplier_id"
                            class="w-full border rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500">

                        <option value="">সাপ্লায়ার নির্বাচন করুন (ঐচ্ছিক)</option>

                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}"
                                @selected(old('supplier_id') == $supplier->id)>
                                {{ $supplier->name }}
                                @if($supplier->phone)
                                    — {{ $supplier->phone }}
                                @endif
                            </option>
                        @endforeach
                    </select>

                    <p class="text-xs text-gray-400 mt-1">
                        Business Settings থেকে সাপ্লায়ার যোগ করতে পারবেন।
                    </p>
                </div>

                {{-- Summary --}}
                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 space-y-4">

                    <div class="flex justify-between gap-3">
                        <span class="text-sm text-gray-600">মোট পণ্যের পরিমাণ</span>
                        <span class="font-bold text-gray-800 text-right">
                            <span x-text="totalQuantity.toFixed(3)"></span>
                            {{ $shopProduct->product?->unit?->short_name }}
                        </span>
                    </div>

                    <div class="flex justify-between gap-3">
                        <span class="text-sm text-gray-600">প্রতি মূল Unit ক্রয়মূল্য</span>
                        <span class="font-semibold text-gray-800">
                            ৳<span x-text="Number(purchasePrice || 0).toFixed(2)"></span>
                        </span>
                    </div>

                    <div class="flex justify-between gap-3">
                        <span class="text-sm text-gray-600">প্রতি মূল Unit বিক্রয়মূল্য</span>
                        <span class="font-semibold text-gray-800">
                            ৳<span x-text="Number(sellingPrice || 0).toFixed(2)"></span>
                        </span>
                    </div>

                    <div class="border-t border-emerald-200 pt-3 flex justify-between gap-3">
                        <span class="font-semibold text-gray-700">মোট ক্রয়মূল্য</span>
                        <span class="text-xl font-bold text-emerald-700">
                            ৳<span x-text="totalAmount.toFixed(2)"></span>
                        </span>
                    </div>
                </div>

                {{-- Note --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        মন্তব্য (ঐচ্ছিক)
                    </label>

                    <textarea name="note"
                              rows="3"
                              placeholder="ক্রয় সম্পর্কিত মন্তব্য লিখুন..."
                              class="w-full border rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500">{{ old('note') }}</textarea>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        onclick="return confirm('এই ক্রয়টি সংরক্ষণ করতে চান?')"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl py-4 transition">
                    ক্রয় সংরক্ষণ করুন
                </button>

            </div>
        </form>
    </div>
</div>
@endsection