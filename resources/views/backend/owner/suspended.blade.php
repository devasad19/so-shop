
@extends('apps.shop_master')

@section('title', 'অ্যাকাউন্ট - OS Shop')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-50 p-4">

    <div class="bg-white max-w-md w-full rounded-3xl border border-gray-100 shadow-lg p-8 text-center">

        <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center text-3xl mx-auto">
            ⚠️
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mt-6">
            অ্যাকাউন্ট বর্তমানে সক্রিয় নেই
        </h1>

        <p class="text-gray-500 leading-7 mt-4">
            আপনার দোকানের অ্যাকাউন্ট বর্তমানে সক্রিয় নেই।
            প্রয়োজনে কর্তৃপক্ষের সঙ্গে যোগাযোগ করুন।
        </p>

        <form action="{{ route('logout') }}" method="POST" class="mt-7">
            @csrf

            <button type="submit"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl py-3 font-semibold cursor-pointer">
                লগআউট
            </button>
        </form>

    </div>

</div>

@endsection
