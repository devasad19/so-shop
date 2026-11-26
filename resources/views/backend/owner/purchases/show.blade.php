@extends('apps.shop_master')

@section('content')

<div class="max-w-3xl mx-auto space-y-4">

    <div class="flex justify-between items-center gap-2">

        <div>
            <h1 class="text-2xl font-bold">ক্রয়ের বিস্তারিত</h1>
            <p class="text-sm text-gray-500">Purchase Details</p>
        </div>

        <a href="{{ route('owner.purchases.index') }}"
           class="bg-gray-100 px-4 py-2 rounded-xl text-sm">
            ফিরে যান
        </a>

    </div>

    <div class="bg-white border rounded-2xl p-5 space-y-4">

        <div>
            <p class="text-sm text-gray-500">পণ্যের নাম</p>
            <h2 class="text-xl font-bold">
                {{ $purchase->shopProduct?->product?->name ?? $purchase->pack_name }}
            </h2>
        </div>

        <hr>

        <div class="grid grid-cols-2 gap-4">

            <div>
                <p class="text-sm text-gray-500">প্যাকের নাম</p>
                <p class="font-semibold">{{ $purchase->pack_name }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">প্যাকের পরিমাণ</p>
                <p class="font-semibold">{{ $purchase->pack_size }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">প্যাক সংখ্যা</p>
                <p class="font-semibold">{{ $purchase->pack_quantity }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">মোট পরিমাণ</p>
                <p class="font-semibold">
                    {{ $purchase->total_quantity }}
                    {{ $purchase->shopProduct?->product?->unit?->short_name }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">প্রতি এককের ক্রয়মূল্য</p>
                <p class="font-semibold">
                    ৳ {{ number_format($purchase->unit_cost, 2) }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">প্রতি প্যাকের মূল্য</p>
                <p class="font-semibold">
                    ৳ {{ number_format($purchase->price_per_pack, 2) }}
                </p>
            </div>

        </div>

        <hr>

        <div class="flex justify-between text-lg">
            <span class="font-semibold">মোট ক্রয়মূল্য</span>

            <strong class="text-emerald-700">
                ৳ {{ number_format($purchase->total_amount, 2) }}
            </strong>
        </div>

        @if($purchase->note)
            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-sm text-gray-500">নোট</p>
                <p>{{ $purchase->note }}</p>
            </div>
        @endif

        <div class="border-t pt-3 text-sm text-gray-500">
            <p>ক্রয়ের তারিখ:
                {{ $purchase->created_at->format('d M Y, h:i A') }}
            </p>

            <p>এন্ট্রি করেছেন:
                {{ $purchase->creator?->name ?? 'N/A' }}
            </p>
        </div>

    </div>

</div>

@endsection