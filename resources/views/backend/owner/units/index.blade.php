
@extends('apps.shop_master')

@section('title', 'Unit - OS Shop')
@section('page_heading', 'Unit ব্যবস্থাপনা')

@section('content')

<div x-data="{ editId: null }"
     class="min-h-screen bg-gray-50 p-4 sm:p-6">

    <div class="max-w-7xl mx-auto">

        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">

            <div>
                <h1 class="text-2xl font-bold text-gray-900">Unit Management</h1>
                <p class="text-sm text-gray-500 mt-1">পণ্যের একক পরিচালনা করুন</p>
            </div>

            <a href="{{ route('owner.dashboard') }}"
               class="bg-white border border-gray-200 px-4 py-2 rounded-xl cursor-pointer">
                ← Dashboard
            </a>

        </div>


        {{-- Messages --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl p-4 mb-5">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-5">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-5">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif


        {{-- Add Unit --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-5 sm:p-6 mb-6">

            <h2 class="font-bold text-lg mb-5">নতুন Unit</h2>

            <form action="{{ route('owner.units.store') }}"
                  method="POST">
                @csrf

                <div class="grid sm:grid-cols-2 gap-4">

                    <div>
                        <label class="text-sm font-medium block mb-2">
                            Unit-এর নাম
                        </label>

                        <input type="text"
                               name="name"
                               required
                               value="{{ old('name') }}"
                               placeholder="যেমন: কেজি"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="text-sm font-medium block mb-2">
                            সংক্ষিপ্ত নাম
                        </label>

                        <input type="text"
                               name="short_name"
                               required
                               value="{{ old('short_name') }}"
                               placeholder="যেমন: kg"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                </div>

                <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-3 rounded-xl font-semibold mt-5 cursor-pointer">
                    + Unit যোগ করুন
                </button>

            </form>

        </div>


        {{-- Search --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-4 mb-5">

            <form method="GET"
                  action="{{ route('owner.units.index') }}"
                  class="flex flex-col sm:flex-row gap-3">

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Unit খুঁজুন..."
                       class="flex-1 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500">

                <button type="submit"
                        class="bg-gray-900 text-white px-5 py-3 rounded-xl cursor-pointer">
                    খুঁজুন
                </button>

                @if(request('search'))
                    <a href="{{ route('owner.units.index') }}"
                       class="text-center border border-gray-200 px-5 py-3 rounded-xl cursor-pointer">
                        Reset
                    </a>
                @endif

            </form>

        </div>


        {{-- Desktop table --}}
        <div class="hidden md:block bg-white rounded-2xl border border-gray-100 overflow-hidden">

            <table class="w-full text-sm">

                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left p-4">#</th>
                        <th class="text-left p-4">Unit</th>
                        <th class="text-left p-4">Short Name</th>
                        <th class="text-center p-4">Products</th>
                        <th class="text-center p-4">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($units as $unit)

                        <tr class="border-t border-gray-100">

                            <td class="p-4">{{ $units->firstItem() + $loop->index }}</td>

                            <td class="p-4 font-medium">{{ $unit->name }}</td>

                            <td class="p-4 text-gray-500">{{ $unit->short_name }}</td>

                            <td class="p-4 text-center">{{ $unit->products_count }}</td>

                            <td class="p-4">

                                <div class="flex justify-center gap-2">

                                    <button type="button"
                                            @click="editId = {{ $unit->id }}"
                                            class="bg-blue-50 text-blue-700 px-3 py-2 rounded-lg cursor-pointer">
                                        Edit
                                    </button>

                                    <form action="{{ route('owner.units.destroy', $unit) }}"
                                          method="POST"
                                          onsubmit="return confirm('Unit মুছে ফেলতে চান?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="bg-red-50 text-red-700 px-3 py-2 rounded-lg cursor-pointer">
                                            Delete
                                        </button>
                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="p-10 text-center text-gray-500">
                                কোনো Unit পাওয়া যায়নি।
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

            <div class="p-4">
                {{ $units->links() }}
            </div>

        </div>


        {{-- Mobile cards --}}
        <div class="md:hidden space-y-4">

            @forelse($units as $unit)

                <div class="bg-white border border-gray-100 rounded-2xl p-5">

                    <div class="flex items-center justify-between">

                        <div>
                            <h3 class="font-bold text-gray-900">{{ $unit->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $unit->short_name }}</p>
                        </div>

                        <span class="bg-emerald-50 text-emerald-700 rounded-lg px-3 py-1 text-xs">
                            {{ $unit->products_count }} পণ্য
                        </span>

                    </div>

                    <div class="flex gap-2 mt-5">

                        <button type="button"
                                @click="editId = {{ $unit->id }}"
                                class="flex-1 bg-blue-50 text-blue-700 py-2.5 rounded-xl cursor-pointer">
                            Edit
                        </button>

                        <form action="{{ route('owner.units.destroy', $unit) }}"
                              method="POST"
                              class="flex-1"
                              onsubmit="return confirm('Unit মুছে ফেলতে চান?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="w-full bg-red-50 text-red-700 py-2.5 rounded-xl cursor-pointer">
                                Delete
                            </button>
                        </form>

                    </div>

                </div>

            @empty

                <div class="bg-white rounded-2xl p-8 text-center text-gray-500">
                    কোনো Unit পাওয়া যায়নি।
                </div>

            @endforelse

            <div>
                {{ $units->links() }}
            </div>

        </div>


        {{-- Edit Modal --}}
        @foreach($units as $unit)

            <div x-show="editId === {{ $unit->id }}"
                 x-cloak
                 class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4">

                <div @click.outside="editId = null"
                     class="bg-white rounded-2xl p-6 w-full max-w-lg">

                    <h3 class="font-bold text-lg mb-5">
                        Unit সম্পাদনা
                    </h3>

                    <form action="{{ route('owner.units.update', $unit) }}"
                          method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">

                            <div>
                                <label class="text-sm font-medium block mb-2">
                                    Unit-এর নাম
                                </label>

                                <input type="text"
                                       name="name"
                                       value="{{ $unit->name }}"
                                       required
                                       class="w-full border border-gray-200 rounded-xl px-4 py-3">
                            </div>

                            <div>
                                <label class="text-sm font-medium block mb-2">
                                    Short Name
                                </label>

                                <input type="text"
                                       name="short_name"
                                       value="{{ $unit->short_name }}"
                                       required
                                       class="w-full border border-gray-200 rounded-xl px-4 py-3">
                            </div>

                        </div>

                        <div class="flex gap-3 mt-5">

                            <button type="submit"
                                    class="bg-emerald-600 text-white px-5 py-3 rounded-xl cursor-pointer">
                                Save
                            </button>

                            <button type="button"
                                    @click="editId = null"
                                    class="border border-gray-200 px-5 py-3 rounded-xl cursor-pointer">
                                Cancel
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        @endforeach

    </div>

</div>

@endsection
