
@extends('apps.shop_master')

@section('title', 'ক্যাটাগরি - OS Shop')
@section('page_heading', 'ক্যাটাগরি ব্যবস্থাপনা')

@section('content')

<div class="min-h-screen bg-gray-50 p-4 sm:p-6">

    <div class="max-w-7xl mx-auto">

        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">পণ্য ক্যাটাগরি</h1>
                <p class="text-sm text-gray-500 mt-1">পণ্যের ক্যাটাগরি পরিচালনা করুন</p>
            </div>

            <a href="{{ route('owner.dashboard') }}"
               class="bg-white border border-gray-200 px-4 py-2 rounded-xl text-sm font-medium cursor-pointer">
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


        {{-- Add Category --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5 sm:p-6 mb-6">

            <h2 class="font-bold text-lg mb-5">নতুন ক্যাটাগরি</h2>

            <form action="{{ route('owner.categories.store') }}"
                  method="POST">
                @csrf

                <div class="grid md:grid-cols-2 gap-4">

                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-2">
                            ক্যাটাগরির নাম
                        </label>

                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               required
                               placeholder="যেমন: মুদি"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-2">
                            বিবরণ (ঐচ্ছিক)
                        </label>

                        <input type="text"
                               name="description"
                               value="{{ old('description') }}"
                               placeholder="ক্যাটাগরির বিবরণ"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                </div>

                <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-3 rounded-xl font-semibold mt-5 cursor-pointer">
                    + ক্যাটাগরি যোগ করুন
                </button>

            </form>

        </div>


        {{-- Search --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-4 mb-5">

            <form method="GET" action="{{ route('owner.categories.index') }}"
                  class="flex flex-col sm:flex-row gap-3">

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="ক্যাটাগরি খুঁজুন..."
                       class="flex-1 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-emerald-500">

                <button type="submit"
                        class="bg-gray-900 hover:bg-gray-800 text-white px-5 py-3 rounded-xl cursor-pointer">
                    খুঁজুন
                </button>

                @if(request('search'))
                    <a href="{{ route('owner.categories.index') }}"
                       class="text-center border border-gray-200 px-5 py-3 rounded-xl cursor-pointer">
                        Reset
                    </a>
                @endif

            </form>

        </div>


        {{-- Desktop table --}}
        <div class="hidden md:block bg-white rounded-2xl border border-gray-100 overflow-hidden">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left p-4">#</th>
                        <th class="text-left p-4">ক্যাটাগরি</th>
                        <th class="text-left p-4">বিবরণ</th>
                        <th class="text-center p-4">পণ্য</th>
                        <th class="text-center p-4">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($categories as $category)

                        <tr class="border-t border-gray-100">

                            <td class="p-4">{{ $categories->firstItem() + $loop->index }}</td>

                            <td class="p-4 font-medium text-gray-900">
                                {{ $category->name }}
                            </td>

                            <td class="p-4 text-gray-500">
                                {{ $category->description ?: '-' }}
                            </td>

                            <td class="p-4 text-center">
                                {{ $category->products_count }}
                            </td>

                            <td class="p-4">

                                <div x-data="{ edit: false }" class="flex justify-center gap-2">

                                    <button type="button"
                                            @click="edit = !edit"
                                            class="px-3 py-2 bg-blue-50 text-blue-700 rounded-lg cursor-pointer">
                                        Edit
                                    </button>

                                    <form action="{{ route('owner.categories.destroy', $category) }}"
                                          method="POST"
                                          onsubmit="return confirm('ক্যাটাগরিটি মুছে ফেলতে চান?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="px-3 py-2 bg-red-50 text-red-700 rounded-lg cursor-pointer">
                                            Delete
                                        </button>
                                    </form>


                                    {{-- Edit form --}}
                                    <div x-show="edit"
                                         x-cloak
                                         class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4">

                                        <div @click.outside="edit = false"
                                             class="bg-white rounded-2xl p-6 w-full max-w-lg">

                                            <h3 class="font-bold text-lg mb-5">
                                                ক্যাটাগরি সম্পাদনা
                                            </h3>

                                            <form action="{{ route('owner.categories.update', $category) }}"
                                                  method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div class="space-y-4">

                                                    <input type="text"
                                                           name="name"
                                                           value="{{ $category->name }}"
                                                           required
                                                           class="w-full border border-gray-200 rounded-xl px-4 py-3">

                                                    <textarea name="description"
                                                              rows="3"
                                                              class="w-full border border-gray-200 rounded-xl px-4 py-3">{{ $category->description }}</textarea>

                                                </div>

                                                <div class="flex gap-3 mt-5">

                                                    <button type="submit"
                                                            class="bg-emerald-600 text-white px-5 py-3 rounded-xl cursor-pointer">
                                                        Save
                                                    </button>

                                                    <button type="button"
                                                            @click="edit = false"
                                                            class="border border-gray-200 px-5 py-3 rounded-xl cursor-pointer">
                                                        Cancel
                                                    </button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="p-10 text-center text-gray-500">
                                কোনো ক্যাটাগরি পাওয়া যায়নি।
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

            <div class="p-4">
                {{ $categories->links() }}
            </div>

        </div>


        {{-- Mobile cards --}}
        <div class="md:hidden space-y-4">

            @forelse($categories as $category)

                <div x-data="{ edit: false }"
                     class="bg-white border border-gray-100 rounded-2xl p-5">

                    <div class="flex justify-between items-start gap-3">

                        <div>
                            <h3 class="font-bold text-gray-900">
                                {{ $category->name }}
                            </h3>

                            <p class="text-sm text-gray-500 mt-2">
                                {{ $category->description ?: 'কোনো বিবরণ নেই' }}
                            </p>
                        </div>

                        <span class="bg-emerald-50 text-emerald-700 px-3 py-1 rounded-lg text-xs">
                            {{ $category->products_count }} পণ্য
                        </span>

                    </div>

                    <div class="flex gap-2 mt-5">

                        <button type="button"
                                @click="edit = true"
                                class="flex-1 bg-blue-50 text-blue-700 py-2.5 rounded-xl cursor-pointer">
                            Edit
                        </button>

                        <form action="{{ route('owner.categories.destroy', $category) }}"
                              method="POST"
                              class="flex-1"
                              onsubmit="return confirm('ক্যাটাগরিটি মুছে ফেলতে চান?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="w-full bg-red-50 text-red-700 py-2.5 rounded-xl cursor-pointer">
                                Delete
                            </button>
                        </form>

                    </div>


                    {{-- Mobile edit modal --}}
                    <div x-show="edit"
                         x-cloak
                         class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4">

                        <div @click.outside="edit = false"
                             class="bg-white rounded-2xl p-5 w-full max-w-lg">

                            <h3 class="font-bold mb-4">ক্যাটাগরি সম্পাদনা</h3>

                            <form action="{{ route('owner.categories.update', $category) }}"
                                  method="POST">
                                @csrf
                                @method('PUT')

                                <input type="text"
                                       name="name"
                                       value="{{ $category->name }}"
                                       required
                                       class="w-full border border-gray-200 rounded-xl px-4 py-3 mb-3">

                                <textarea name="description"
                                          rows="3"
                                          class="w-full border border-gray-200 rounded-xl px-4 py-3">{{ $category->description }}</textarea>

                                <div class="flex gap-3 mt-4">

                                    <button type="submit"
                                            class="flex-1 bg-emerald-600 text-white py-3 rounded-xl cursor-pointer">
                                        Save
                                    </button>

                                    <button type="button"
                                            @click="edit = false"
                                            class="flex-1 border border-gray-200 py-3 rounded-xl cursor-pointer">
                                        Cancel
                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            @empty

                <div class="bg-white rounded-2xl p-8 text-center text-gray-500">
                    কোনো ক্যাটাগরি পাওয়া যায়নি।
                </div>

            @endforelse

            <div>
                {{ $categories->links() }}
            </div>

        </div>

    </div>

</div>

@endsection
