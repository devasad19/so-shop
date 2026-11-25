
@extends('apps.shop_master')

@section('title', 'অ্যাকাউন্ট তৈরি করুন - OS Shop')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-teal-50 flex items-center justify-center p-4">

    <div class="w-full max-w-lg">

        {{-- Logo --}}
        <div class="text-center mb-8">

            <a href="{{ route('portal.home') }}"
               class="inline-flex items-center gap-3 cursor-pointer">

                <div class="w-12 h-12 bg-emerald-600 rounded-2xl flex items-center justify-center text-white font-bold text-xl">
                    OS
                </div>

                <div class="text-left">
                    <h1 class="text-2xl font-bold text-gray-900">OS Shop</h1>
                    <p class="text-xs text-gray-500">SHOP MANAGEMENT</p>
                </div>

            </a>

            <h2 class="text-2xl font-bold text-gray-900 mt-6">
                নতুন অ্যাকাউন্ট তৈরি করুন
            </h2>

            <p class="text-gray-500 mt-2">
                আপনার দোকানের হিসাব পরিচালনা শুরু করুন
            </p>

        </div>


        {{-- Form --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-xl p-6 sm:p-8">

            @if($errors->any())
                <div class="bg-red-50 border border-red-100 rounded-xl p-4 mb-5 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.submit') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Owner name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        আপনার নাম
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           autocomplete="name"
                           placeholder="আপনার নাম লিখুন"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>


                {{-- Business name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        দোকানের নাম
                    </label>

                    <input type="text"
                           name="business_name"
                           value="{{ old('business_name') }}"
                           required
                           placeholder="আপনার দোকানের নাম"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>


                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        মোবাইল নম্বর
                    </label>

                    <input type="tel"
                           name="phone"
                           value="{{ old('phone') }}"
                           required
                           autocomplete="tel"
                           placeholder="01XXXXXXXXX"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>


                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        ইমেইল
                    </label>

                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="email"
                           placeholder="example@email.com"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>


                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        পাসওয়ার্ড
                    </label>

                    <input type="password"
                           name="password"
                           required
                           minlength="8"
                           autocomplete="new-password"
                           placeholder="কমপক্ষে ৮ অক্ষর"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>


                {{-- Confirm Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        পাসওয়ার্ড নিশ্চিত করুন
                    </label>

                    <input type="password"
                           name="password_confirmation"
                           required
                           minlength="8"
                           autocomplete="new-password"
                           placeholder="পাসওয়ার্ড আবার লিখুন"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>


                {{-- Submit --}}
                <button type="submit"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3.5 rounded-xl font-semibold transition cursor-pointer">
                    অ্যাকাউন্ট তৈরি করুন
                </button>

            </form>


            <div class="text-center text-sm text-gray-500 mt-6">

                ইতোমধ্যে অ্যাকাউন্ট আছে?

                <a href="{{ route('login') }}"
                   class="text-emerald-600 font-semibold hover:text-emerald-700 cursor-pointer">
                    লগইন করুন
                </a>

            </div>

        </div>


        <div class="text-center mt-6">
            <a href="{{ route('portal.home') }}"
               class="text-sm text-gray-500 hover:text-emerald-600 cursor-pointer">
                ← ওয়েবসাইটে ফিরে যান
            </a>
        </div>

    </div>

</div>

@endsection
