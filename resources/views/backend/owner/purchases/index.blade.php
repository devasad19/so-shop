@extends('apps.shop_master')

@section('content')

<div class="max-w-7xl mx-auto space-y-5">

    <div class="flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">ক্রয়ের ইতিহাস</h1>
            <p class="text-sm text-gray-500">Purchase History</p>
        </div>

        <a href="{{ route('owner.purchases.create') }}"
           class="bg-emerald-600 text-white px-4 py-3 rounded-xl font-semibold">
            + নতুন ক্রয়
        </a>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-2 gap-3">

        <div class="bg-white border rounded-2xl p-4">
            <p class="text-sm text-gray-500">মোট ক্রয়মূল্য</p>
            <h2 class="text-xl font-bold text-emerald-700 mt-1">
                ৳ {{ number_format($totalAmount, 2) }}
            </h2>
        </div>

        <div class="bg-white border rounded-2xl p-4">
            <p class="text-sm text-gray-500">মোট ক্রয় পরিমাণ</p>
            <h2 class="text-xl font-bold text-gray-800 mt-1">
                {{ number_format($totalQuantity, 3) }}
            </h2>
        </div>

    </div>

    {{-- Search --}}
    <form method="GET" class="bg-white border rounded-2xl p-4 space-y-3">

        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="পণ্যের নাম লিখে খুঁজুন..."
               class="w-full border rounded-xl px-3 py-3">

        <div class="grid grid-cols-2 gap-2">

            <input type="date"
                   name="from"
                   value="{{ request('from') }}"
                   class="w-full border rounded-xl px-3 py-2">

            <input type="date"
                   name="to"
                   value="{{ request('to') }}"
                   class="w-full border rounded-xl px-3 py-2">

        </div>

        <div class="flex gap-2">

            <button class="bg-gray-800 text-white px-5 py-2 rounded-lg">
                খুঁজুন
            </button>

            <a href="{{ route('owner.purchases.index') }}"
               class="bg-gray-100 px-5 py-2 rounded-lg">
                Reset
            </a>

        </div>

    </form>

    {{-- Purchase List --}}
    <div class="bg-white border rounded-2xl overflow-hidden">

        <div class="p-4 border-b font-bold">
            ক্রয়ের তালিকা
        </div>

        @forelse($purchases as $purchase)

            <a href="{{ route('owner.purchases.show', $purchase->id) }}"
               class="block p-4 border-b hover:bg-gray-50">

                <div class="flex justify-between gap-3">

                    <div class="min-w-0">

                        <h3 class="font-semibold text-gray-800">
                            {{ $purchase->shopProduct?->product?->name ?? $purchase->pack_name }}
                        </h3>

                        <p class="text-xs text-gray-500 mt-1">
                            {{ $purchase->created_at->format('d M Y, h:i A') }}
                        </p>

                        <p class="text-sm text-gray-600 mt-2">
                            {{ $purchase->pack_quantity }}
                            ×
                            {{ $purchase->pack_size }}
                            =
                            {{ $purchase->total_quantity }}
                            {{ $purchase->shopProduct?->product?->unit?->short_name }}
                        </p>

                    </div>

                    <div class="text-right shrink-0">

                        <p class="font-bold text-emerald-700">
                            ৳ {{ number_format($purchase->total_amount, 2) }}
                        </p>

                        <span class="text-xs text-gray-500">
                            বিস্তারিত দেখুন →
                        </span>

                    </div>

                </div>

            </a>

        @empty

            <div class="text-center py-12 text-gray-400">
                কোনো ক্রয়ের তথ্য পাওয়া যায়নি।
            </div>

        @endforelse

    </div>

    {{ $purchases->links() }}

</div>

@endsection