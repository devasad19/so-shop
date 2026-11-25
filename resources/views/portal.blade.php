
@extends('apps.shop_master')

@section('title', 'OS Shop Management - সহজ দোকান ব্যবস্থাপনা')

@section('content')

<div x-data="{ mobileMenu: false }" class="overflow-hidden">

    {{-- Navbar --}}
    <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-xl border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 md:h-20">

                {{-- Logo --}}
                <a href="{{ route('portal.home') }}" class="flex items-center gap-2.5 cursor-pointer">
                    <div class="w-11 h-11 bg-emerald-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-sm">
                        OS
                    </div>

                    <div>
                        <h1 class="text-xl font-bold text-gray-900 leading-tight">OS Shop</h1>
                        <p class="text-[11px] text-gray-500">SHOP MANAGEMENT</p>
                    </div>
                </a>

                {{-- Desktop menu --}}
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-gray-600 hover:text-emerald-600 cursor-pointer">ফিচার</a>
                    <a href="#about" class="text-gray-600 hover:text-emerald-600 cursor-pointer">আমাদের সম্পর্কে</a>
                    <a href="#contact" class="text-gray-600 hover:text-emerald-600 cursor-pointer">যোগাযোগ</a>
                </div>

                {{-- Desktop buttons --}}
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 font-medium text-gray-700 hover:text-emerald-600 cursor-pointer">
                        লগইন
                    </a>

                    <a href="{{ route('register') }}"
                       class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-semibold transition cursor-pointer">
                        শুরু করুন
                    </a>
                </div>

                {{-- Mobile menu button --}}
                <button type="button"
                        @click="mobileMenu = !mobileMenu"
                        class="md:hidden w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200 cursor-pointer">
                    <span x-show="!mobileMenu" class="text-xl">☰</span>
                    <span x-show="mobileMenu" x-cloak class="text-xl">✕</span>
                </button>

            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="mobileMenu"
             x-cloak
             x-transition
             class="md:hidden border-t border-gray-100 bg-white">

            <div class="px-4 py-4 space-y-3">

                <a href="#features"
                   @click="mobileMenu = false"
                   class="block p-3 rounded-xl hover:bg-gray-50 cursor-pointer">
                    ফিচারসমূহ
                </a>

                <a href="#about"
                   @click="mobileMenu = false"
                   class="block p-3 rounded-xl hover:bg-gray-50 cursor-pointer">
                    আমাদের সম্পর্কে
                </a>

                <a href="#contact"
                   @click="mobileMenu = false"
                   class="block p-3 rounded-xl hover:bg-gray-50 cursor-pointer">
                    যোগাযোগ
                </a>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <a href="{{ route('login') }}"
                       class="text-center border border-gray-200 rounded-xl p-3 font-medium cursor-pointer">
                        লগইন
                    </a>

                    <a href="{{ route('register') }}"
                       class="text-center bg-emerald-600 text-white rounded-xl p-3 font-semibold cursor-pointer">
                        শুরু করুন
                    </a>
                </div>

            </div>
        </div>
    </nav>


    {{-- Hero --}}
    <section class="bg-gradient-to-br from-emerald-50 via-white to-teal-50">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-24 lg:py-28">

            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                {{-- Hero text --}}
                <div>

                    <div class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-700 px-4 py-2 rounded-full text-sm font-medium">
                        <span>✦</span>
                        <span>স্মার্ট দোকান ব্যবস্থাপনা</span>
                    </div>

                    <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mt-6">
                        দোকানের সব হিসাব

                        <span class="text-emerald-600 block mt-2">
                            এখন এক জায়গায়
                        </span>
                    </h2>

                    <p class="text-gray-600 text-base md:text-lg leading-8 mt-6 max-w-xl">
                        বিক্রি, পণ্য, স্টক, ক্রয়, বাকি, আয় ও ব্যয়।
                        আপনার দোকানের দৈনিক হিসাব রাখুন সহজে,
                        সুন্দরভাবে ও নির্ভুলভাবে।
                    </p>

                    <div class="flex flex-col sm:flex-row gap-3 mt-8">

                        <a href="{{ route('register') }}"
                           class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-7 py-3.5 rounded-xl font-semibold transition shadow-sm cursor-pointer">
                            বিনামূল্যে শুরু করুন
                            <span>→</span>
                        </a>

                        <a href="#features"
                           class="inline-flex items-center justify-center border border-gray-200 bg-white hover:bg-gray-50 px-7 py-3.5 rounded-xl font-semibold transition cursor-pointer">
                            ফিচার দেখুন
                        </a>

                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-9 text-sm text-gray-600">

                        <div class="flex items-center gap-2">
                            <span class="text-emerald-600">✓</span>
                            <span>সহজ ব্যবহার</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-emerald-600">✓</span>
                            <span>দৈনিক হিসাব</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-emerald-600">✓</span>
                            <span>মোবাইল উপযোগী</span>
                        </div>

                    </div>

                </div>


                {{-- Dashboard preview --}}
                <div class="relative">

                    <div class="absolute -top-8 -right-8 w-40 h-40 bg-emerald-200/40 rounded-full blur-3xl"></div>

                    <div class="relative bg-white rounded-3xl shadow-xl border border-gray-100 p-4 sm:p-6">

                        {{-- Preview header --}}
                        <div class="flex items-center justify-between gap-3 mb-6">

                            <div>
                                <h3 class="text-xl font-bold text-gray-900">
                                    ড্যাশবোর্ড
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    দোকানের সংক্ষিপ্ত হিসাব
                                </p>
                            </div>

                            <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center text-2xl">
                                📊
                            </div>

                        </div>

                        {{-- Stats --}}
                        <div class="grid grid-cols-2 gap-3 sm:gap-4">

                            <div class="bg-blue-50 rounded-2xl p-4 sm:p-5">
                                <p class="text-sm text-blue-700">আজকের বিক্রি</p>
                                <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">
                                    ৳ ১২,৫০০
                                </p>
                                <p class="text-xs text-gray-400 mt-2">নমুনা হিসাব</p>
                            </div>

                            <div class="bg-emerald-50 rounded-2xl p-4 sm:p-5">
                                <p class="text-sm text-emerald-700">আজকের আয়</p>
                                <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">
                                    ৳ ১,৫০০
                                </p>
                                <p class="text-xs text-gray-400 mt-2">নমুনা হিসাব</p>
                            </div>

                            <div class="bg-orange-50 rounded-2xl p-4 sm:p-5">
                                <p class="text-sm text-orange-700">আজকের ব্যয়</p>
                                <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">
                                    ৳ ৮০০
                                </p>
                                <p class="text-xs text-gray-400 mt-2">নমুনা হিসাব</p>
                            </div>

                            <div class="bg-purple-50 rounded-2xl p-4 sm:p-5">
                                <p class="text-sm text-purple-700">কাস্টমার বাকি</p>
                                <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">
                                    ৳ ৩,২০০
                                </p>
                                <p class="text-xs text-gray-400 mt-2">নমুনা হিসাব</p>
                            </div>

                        </div>

                        {{-- Product summary --}}
                        <div class="bg-gray-50 rounded-2xl p-4 sm:p-5 mt-4">

                            <div class="flex items-center justify-between">

                                <div>
                                    <p class="text-gray-500 text-sm">পণ্য ব্যবস্থাপনা</p>
                                    <p class="font-semibold text-gray-900 mt-1">
                                        স্টক রাখুন নিয়ন্ত্রণে
                                    </p>
                                </div>

                                <div class="text-3xl">📦</div>

                            </div>

                        </div>

                        <p class="text-center text-xs text-gray-400 mt-5">
                            ড্যাশবোর্ডের নমুনা প্রদর্শনী
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- Features --}}
    <section id="features" class="py-16 md:py-24">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="max-w-2xl mx-auto text-center">

                <span class="text-emerald-600 font-semibold">
                    আমাদের ফিচারসমূহ
                </span>

                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-4">
                    দোকান পরিচালনার সব প্রয়োজনীয় সুবিধা
                </h2>

                <p class="text-gray-500 leading-7 mt-5">
                    দৈনিক দোকানের কাজ থেকে শুরু করে আর্থিক হিসাব
                    পর্যন্ত প্রয়োজনীয় সবকিছু এক প্ল্যাটফর্মে।
                </p>

            </div>


            @php
                $features = [
                    ['icon' => '🛒', 'title' => 'POS বিক্রি', 'desc' => 'দ্রুত বিক্রি, কার্ট, বিল ও পেমেন্ট পরিচালনা।'],
                    ['icon' => '📦', 'title' => 'পণ্য ও স্টক', 'desc' => 'পণ্য, ক্যাটাগরি, ইউনিট, বারকোড ও স্টক হিসাব।'],
                    ['icon' => '🧾', 'title' => 'ক্রয় ব্যবস্থাপনা', 'desc' => 'পণ্য ক্রয়, ক্রয় হিসাব ও ক্রয়ের রেকর্ড।'],
                    ['icon' => '👥', 'title' => 'কাস্টমার', 'desc' => 'কাস্টমার তথ্য, বাকি ও বাকি আদায় পরিচালনা।'],
                    ['icon' => '🏭', 'title' => 'সরবরাহকারী', 'desc' => 'Supplier তথ্য, দেনা ও পরিশোধের হিসাব।'],
                    ['icon' => '💰', 'title' => 'আয় ও ব্যয়', 'desc' => 'দোকানের আয়, ব্যয় ও আর্থিক হিসাব পরিচালনা।'],
                    ['icon' => '📒', 'title' => 'হিসাবের খাতা', 'desc' => 'ক্যাশ, ব্যাংক ও অন্যান্য পেমেন্ট হিসাব।'],
                    ['icon' => '📊', 'title' => 'রিপোর্ট', 'desc' => 'বিক্রি, ক্রয়, আয়, ব্যয়, স্টক ও বাকি রিপোর্ট।'],
                    ['icon' => '↩️', 'title' => 'পণ্য ফেরত', 'desc' => 'বিক্রিত ও ক্রয়কৃত পণ্য ফেরত পরিচালনা।'],
                    ['icon' => '🕒', 'title' => 'দৈনিক হিসাব', 'desc' => 'দিনের শুরু ও শেষের ক্যাশ হিসাব।'],
                    ['icon' => '👨‍💼', 'title' => 'কর্মচারী', 'desc' => 'কর্মচারী, ভূমিকা ও কাজের অনুমতি পরিচালনা।'],
                    ['icon' => '📱', 'title' => 'মোবাইল ও PWA', 'desc' => 'মোবাইল, ট্যাবলেট ও কম্পিউটারে ব্যবহার।'],
                ];
            @endphp


            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 mt-12">

                @foreach($features as $feature)

                    <div class="group bg-white border border-gray-100 hover:border-emerald-200 rounded-2xl p-6 hover:shadow-lg transition duration-300">

                        <div class="w-14 h-14 bg-emerald-50 group-hover:bg-emerald-100 rounded-2xl flex items-center justify-center text-3xl transition">
                            {{ $feature['icon'] }}
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mt-5">
                            {{ $feature['title'] }}
                        </h3>

                        <p class="text-gray-500 leading-7 mt-3">
                            {{ $feature['desc'] }}
                        </p>

                    </div>

                @endforeach

            </div>

        </div>

    </section>


    {{-- About --}}
    <section id="about" class="bg-gray-50 py-16 md:py-24">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid lg:grid-cols-2 gap-12 items-center">

                <div>

                    <span class="text-emerald-600 font-semibold">
                        OS Shop Management
                    </span>

                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mt-4">
                        দোকানের হিসাব রাখার জন্য
                        সহজ ও আধুনিক সমাধান
                    </h2>

                    <p class="text-gray-600 leading-8 mt-6">
                        ছোট ও মাঝারি দোকানের দৈনিক কার্যক্রম
                        সহজভাবে পরিচালনা করার উদ্দেশ্যে OS Shop Management
                        তৈরি করা হচ্ছে। দোকানের বিক্রি, ক্রয়, পণ্য, বাকি,
                        আয় ও ব্যয়ের হিসাব একটি জায়গায় পরিচালনা করা যাবে।
                    </p>

                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-semibold mt-8 cursor-pointer">
                        অ্যাকাউন্ট তৈরি করুন →
                    </a>

                </div>


                <div class="grid grid-cols-2 gap-4">

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="text-3xl">⚡</div>
                        <h3 class="font-bold text-gray-900 mt-4">দ্রুত পরিচালনা</h3>
                        <p class="text-sm text-gray-500 leading-7 mt-3">
                            দৈনন্দিন কাজ দ্রুত সম্পন্ন করুন।
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="text-3xl">📱</div>
                        <h3 class="font-bold text-gray-900 mt-4">মোবাইল উপযোগী</h3>
                        <p class="text-sm text-gray-500 leading-7 mt-3">
                            মোবাইল ও কম্পিউটার থেকে ব্যবহার করুন।
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="text-3xl">📋</div>
                        <h3 class="font-bold text-gray-900 mt-4">গোছানো হিসাব</h3>
                        <p class="text-sm text-gray-500 leading-7 mt-3">
                            সব হিসাব একটি জায়গায় রাখুন।
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="text-3xl">🔐</div>
                        <h3 class="font-bold text-gray-900 mt-4">নিজস্ব অ্যাকাউন্ট</h3>
                        <p class="text-sm text-gray-500 leading-7 mt-3">
                            নিজস্ব দোকানের হিসাব আলাদাভাবে পরিচালনা করুন।
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- CTA --}}
    <section class="py-16 md:py-24">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-emerald-600 rounded-3xl px-6 py-14 md:px-16 md:py-16 text-center text-white">

                <h2 class="text-3xl md:text-4xl font-bold leading-tight">
                    আপনার দোকানের হিসাব শুরু করুন আজই
                </h2>

                <p class="text-emerald-100 leading-8 max-w-2xl mx-auto mt-5">
                    দৈনিক দোকান পরিচালনা ও হিসাবকে আরও সহজ,
                    সুন্দর এবং গোছানো করতে OS Shop ব্যবহার করুন।
                </p>

                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2 bg-white text-emerald-700 hover:bg-emerald-50 px-7 py-3.5 rounded-xl font-bold mt-8 transition cursor-pointer">
                    অ্যাকাউন্ট তৈরি করুন
                    <span>→</span>
                </a>

            </div>

        </div>

    </section>


    {{-- Footer --}}
    <footer id="contact" class="border-t border-gray-100 bg-white">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            <div class="grid sm:grid-cols-2 gap-8">

                <div>

                    <div class="flex items-center gap-3">

                        <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white font-bold">
                            OS
                        </div>

                        <h3 class="text-xl font-bold text-gray-900">
                            OS Shop Management
                        </h3>

                    </div>

                    <p class="text-gray-500 leading-7 mt-4 max-w-md">
                        দোকানের বিক্রি, ক্রয়, স্টক, আয়, ব্যয়
                        ও দৈনিক হিসাব পরিচালনার সহজ সমাধান।
                    </p>

                </div>


                <div class="sm:text-right">

                    <h4 class="font-bold text-gray-900">
                        অ্যাকাউন্ট
                    </h4>

                    <div class="flex flex-col sm:items-end gap-3 mt-4 text-gray-500">

                        <a href="{{ route('login') }}"
                           class="hover:text-emerald-600 cursor-pointer">
                            লগইন
                        </a>

                        <a href="{{ route('register') }}"
                           class="hover:text-emerald-600 cursor-pointer">
                            রেজিস্ট্রেশন
                        </a>

                    </div>

                </div>

            </div>


            <div class="border-t border-gray-100 mt-8 pt-6 text-center text-sm text-gray-400">

                © {{ date('Y') }} OS Shop Management.
                All rights reserved.

            </div>

        </div>

    </footer>

</div>

@endsection
