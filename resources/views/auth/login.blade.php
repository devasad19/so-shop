
@extends('apps.shop_master')

@section('title', 'লগইন - OS Shop')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-teal-50 flex items-center justify-center p-4">

    <div class="w-full max-w-md">

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
                স্বাগতম
            </h2>

            <p class="text-gray-500 mt-2">
                আপনার দোকানের অ্যাকাউন্টে লগইন করুন
            </p>

        </div>


        {{-- Login Card --}}
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


            <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        ইমেইল
                    </label>

                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
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
                           autocomplete="current-password"
                           placeholder="আপনার পাসওয়ার্ড"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>


                {{-- Remember --}}
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox"
                           name="remember"
                           value="1"
                           class="w-4 h-4 accent-emerald-600 cursor-pointer">

                    <span>আমাকে মনে রাখুন</span>
                </label>


                {{-- Submit --}}
                <button type="submit"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3.5 rounded-xl font-semibold transition cursor-pointer">
                    লগইন করুন
                </button>

            </form>


            <div class="text-center text-sm text-gray-500 mt-6">

                নতুন ব্যবহারকারী?

                <a href="{{ route('register') }}"
                   class="text-emerald-600 font-semibold hover:text-emerald-700 cursor-pointer">
                    অ্যাকাউন্ট তৈরি করুন
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
