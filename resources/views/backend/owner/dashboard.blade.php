
@extends('apps.shop_master')

@section('title', 'ড্যাশবোর্ড - OS Shop')

@section('content')
 


    {{-- Main area --}}
    <div class="lg:ml-72 min-h-screen">


        {{-- Header --}}
        <header class="sticky top-0 z-30 bg-white border-b border-gray-100">

            <div class="h-16 px-4 sm:px-6 flex items-center justify-between gap-4">


                {{-- Left --}}
                <div class="flex items-center gap-3">

                    <button type="button"
                            @click="sidebarOpen = true"
                            class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200 cursor-pointer">
                        ☰
                    </button>

                    <div>
                        <h2 class="font-bold text-gray-900">
                            ড্যাশবোর্ড
                        </h2>

                        <p class="text-xs text-gray-500 hidden sm:block">
                            আপনার দোকানের সংক্ষিপ্ত হিসাব
                        </p>
                    </div>

                </div>


                {{-- Right --}}
                <div class="relative">

                    <button type="button"
                            @click="profileOpen = !profileOpen"
                            class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 cursor-pointer">

                        <div class="w-9 h-9 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>

                        <div class="hidden sm:block text-left">
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $user->name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                Owner
                            </p>
                        </div>

                        <span class="hidden sm:block text-gray-400">⌄</span>

                    </button>


                    {{-- Profile dropdown --}}
                    <div x-show="profileOpen"
                         x-cloak
                         @click.outside="profileOpen = false"
                         x-transition
                         class="absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl border border-gray-100 shadow-lg p-2">

                        <div class="px-3 py-3 border-b border-gray-100">

                            <p class="font-semibold text-gray-900">
                                {{ $user->name }}
                            </p>

                            <p class="text-xs text-gray-500 break-all mt-1">
                                {{ $user->email }}
                            </p>

                        </div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf

                            <button type="submit"
                                    class="w-full flex items-center gap-3 px-3 py-3 text-red-600 hover:bg-red-50 rounded-xl text-sm font-medium cursor-pointer">
                                <span>🚪</span>
                                <span>লগআউট</span>
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </header>


        {{-- Dashboard content --}}
        <main class="p-4 sm:p-6 lg:p-8 pb-24 lg:pb-8">


            {{-- Welcome --}}
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl sm:rounded-3xl p-5 sm:p-8 text-white mb-6">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">

                    <div>

                        <p class="text-emerald-100 text-sm">
                            স্বাগতম,
                        </p>

                        <h2 class="text-2xl sm:text-3xl font-bold mt-2">
                            {{ $user->name }}
                        </h2>

                        <p class="text-emerald-100 mt-3 leading-7">
                            {{ $business->name }}-এর দৈনিক হিসাব পরিচালনা করুন।
                        </p>

                    </div>

                    <div class="hidden sm:flex w-20 h-20 bg-white/10 rounded-2xl items-center justify-center text-4xl">
                        🏪
                    </div>

                </div>

            </div>


            {{-- Summary cards --}}
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">


                {{-- Sales --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">

                    <div class="flex items-center justify-between gap-2">

                        <p class="text-sm text-gray-500">
                            আজকের বিক্রি
                        </p>

                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                            🛒
                        </div>

                    </div>

                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mt-4">
                        ৳ ০.০০
                    </h3>

                    <p class="text-xs text-gray-400 mt-2">
                        আজকের মোট বিক্রি
                    </p>

                </div>


                {{-- Income --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">

                    <div class="flex items-center justify-between gap-2">

                        <p class="text-sm text-gray-500">
                            আজকের আয়
                        </p>

                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                            💰
                        </div>

                    </div>

                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mt-4">
                        ৳ ০.০০
                    </h3>

                    <p class="text-xs text-gray-400 mt-2">
                        আজকের আয়
                    </p>

                </div>


                {{-- Expense --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">

                    <div class="flex items-center justify-between gap-2">

                        <p class="text-sm text-gray-500">
                            আজকের ব্যয়
                        </p>

                        <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center">
                            📉
                        </div>

                    </div>

                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mt-4">
                        ৳ ০.০০
                    </h3>

                    <p class="text-xs text-gray-400 mt-2">
                        আজকের ব্যয়
                    </p>

                </div>


                {{-- Due --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">

                    <div class="flex items-center justify-between gap-2">

                        <p class="text-sm text-gray-500">
                            কাস্টমার বাকি
                        </p>

                        <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center">
                            👥
                        </div>

                    </div>

                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mt-4">
                        ৳ ০.০০
                    </h3>

                    <p class="text-xs text-gray-400 mt-2">
                        মোট বাকি
                    </p>

                </div>

            </div>


            {{-- Quick actions --}}
            <div class="mt-8">

                <div class="flex items-center justify-between mb-4">

                    <div>
                        <h3 class="text-lg font-bold text-gray-900">
                            দ্রুত কাজ
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            প্রয়োজনীয় কাজের shortcut
                        </p>
                    </div>

                </div>


                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">


                    {{-- Sale --}}
                    <button type="button"
                            disabled
                            class="bg-white border border-gray-100 hover:border-emerald-200 hover:shadow-md rounded-2xl p-5 text-left transition cursor-pointer opacity-70">

                        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-2xl">
                            🛒
                        </div>

                        <h4 class="font-bold text-gray-900 mt-4">
                            নতুন বিক্রি
                        </h4>

                        <p class="text-xs text-gray-400 mt-2">
                            Coming soon
                        </p>

                    </button>


                    {{-- Product --}}
                    <button type="button"
                            disabled
                            class="bg-white border border-gray-100 hover:border-blue-200 hover:shadow-md rounded-2xl p-5 text-left transition cursor-pointer opacity-70">

                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-2xl">
                            📦
                        </div>

                        <h4 class="font-bold text-gray-900 mt-4">
                            পণ্য যোগ করুন
                        </h4>

                        <p class="text-xs text-gray-400 mt-2">
                            Coming soon
                        </p>

                    </button>


                    {{-- Purchase --}}
                    <button type="button"
                            disabled
                            class="bg-white border border-gray-100 hover:border-orange-200 hover:shadow-md rounded-2xl p-5 text-left transition cursor-pointer opacity-70">

                        <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-2xl">
                            📥
                        </div>

                        <h4 class="font-bold text-gray-900 mt-4">
                            নতুন ক্রয়
                        </h4>

                        <p class="text-xs text-gray-400 mt-2">
                            Coming soon
                        </p>

                    </button>


                    {{-- Customer --}}
                    <button type="button"
                            disabled
                            class="bg-white border border-gray-100 hover:border-purple-200 hover:shadow-md rounded-2xl p-5 text-left transition cursor-pointer opacity-70">

                        <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-2xl">
                            👥
                        </div>

                        <h4 class="font-bold text-gray-900 mt-4">
                            কাস্টমার
                        </h4>

                        <p class="text-xs text-gray-400 mt-2">
                            Coming soon
                        </p>

                    </button>

                </div>

            </div>


            {{-- Module overview --}}
            <div class="mt-8 bg-white border border-gray-100 rounded-2xl p-5 sm:p-6">

                <div class="mb-6">

                    <h3 class="text-lg font-bold text-gray-900">
                        আপনার দোকান পরিচালনা
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        প্রয়োজনীয় মডিউলগুলো পর্যায়ক্রমে চালু করা হবে।
                    </p>

                </div>


                <div class="grid sm:grid-cols-2 gap-4">


                    {{-- Product --}}
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">

                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-2xl">
                            📦
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-900">
                                পণ্য ও স্টক
                            </h4>

                            <p class="text-xs text-gray-500 mt-1">
                                পণ্য ও স্টক পরিচালনা
                            </p>
                        </div>

                        <span class="ml-auto text-xs text-gray-400">
                            অপেক্ষমাণ
                        </span>

                    </div>


                    {{-- Purchase --}}
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">

                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-2xl">
                            📥
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-900">
                                ক্রয়
                            </h4>

                            <p class="text-xs text-gray-500 mt-1">
                                পণ্য ক্রয় ও হিসাব
                            </p>
                        </div>

                        <span class="ml-auto text-xs text-gray-400">
                            অপেক্ষমাণ
                        </span>

                    </div>


                    {{-- Sales --}}
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">

                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-2xl">
                            🛒
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-900">
                                বিক্রি
                            </h4>

                            <p class="text-xs text-gray-500 mt-1">
                                POS ও বিক্রির হিসাব
                            </p>
                        </div>

                        <span class="ml-auto text-xs text-gray-400">
                            অপেক্ষমাণ
                        </span>

                    </div>


                    {{-- Accounts --}}
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">

                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-2xl">
                            💰
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-900">
                                আয় ও ব্যয়
                            </h4>

                            <p class="text-xs text-gray-500 mt-1">
                                আর্থিক হিসাব
                            </p>
                        </div>

                        <span class="ml-auto text-xs text-gray-400">
                            অপেক্ষমাণ
                        </span>

                    </div>

                </div>

            </div>


            {{-- Shop info --}}
            <div class="mt-8 bg-white border border-gray-100 rounded-2xl p-5 sm:p-6">

                <h3 class="text-lg font-bold text-gray-900 mb-5">
                    দোকানের তথ্য
                </h3>

                <div class="space-y-4">

                    <div class="flex flex-col sm:flex-row sm:justify-between gap-2">
                        <span class="text-sm text-gray-500">দোকানের নাম</span>
                        <span class="text-sm font-medium text-gray-900">{{ $business->name }}</span>
                    </div>

                    <div class="border-t border-gray-100"></div>

                    <div class="flex flex-col sm:flex-row sm:justify-between gap-2">
                        <span class="text-sm text-gray-500">মালিক</span>
                        <span class="text-sm font-medium text-gray-900">{{ $user->name }}</span>
                    </div>

                    <div class="border-t border-gray-100"></div>

                    <div class="flex flex-col sm:flex-row sm:justify-between gap-2">
                        <span class="text-sm text-gray-500">ইমেইল</span>
                        <span class="text-sm font-medium text-gray-900 break-all">{{ $user->email }}</span>
                    </div>

                    <div class="border-t border-gray-100"></div>

                    <div class="flex flex-col sm:flex-row sm:justify-between gap-2">
                        <span class="text-sm text-gray-500">অ্যাকাউন্টের ভূমিকা</span>
                        <span class="text-sm font-medium text-emerald-700">Owner</span>
                    </div>

                </div>

            </div>


        </main>

    </div>


    {{-- Mobile bottom navigation --}}
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-30 bg-white border-t border-gray-100">

        <div class="grid grid-cols-4 h-16">

            <a href="{{ route('owner.dashboard') }}"
               class="flex flex-col items-center justify-center gap-1 text-emerald-600 cursor-pointer">
                <span class="text-lg">📊</span>
                <span class="text-[11px] font-medium">Home</span>
            </a>

            <button type="button"
                    disabled
                    class="flex flex-col items-center justify-center gap-1 text-gray-400 cursor-pointer">
                <span class="text-lg">🛒</span>
                <span class="text-[11px]">বিক্রি</span>
            </button>

            <button type="button"
                    disabled
                    class="flex flex-col items-center justify-center gap-1 text-gray-400 cursor-pointer">
                <span class="text-lg">📦</span>
                <span class="text-[11px]">পণ্য</span>
            </button>

            <button type="button"
                    @click="profileOpen = !profileOpen"
                    class="flex flex-col items-center justify-center gap-1 text-gray-400 cursor-pointer">
                <span class="text-lg">👤</span>
                <span class="text-[11px]">Account</span>
            </button>

        </div>

    </nav>


</div>

@endsection
