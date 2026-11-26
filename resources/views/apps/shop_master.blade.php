
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#059669">

    <title>@yield('title', 'OS Shop Management')</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Hind Siliguri', 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        html {
            scroll-behavior: smooth;
        }
    </style>

    @stack('styles')
</head>

<body class="min-h-screen bg-gray-50 text-gray-800 antialiased">

<div x-data="{ sidebarOpen: false, profileOpen: false }"
     class="min-h-screen">

    {{-- Mobile Sidebar Overlay --}}
    <div x-show="sidebarOpen"
         x-cloak
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/40 z-40 lg:hidden"
         style="display:none">
    </div>

    {{-- Sidebar --}}
    <aside
        class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-200 flex flex-col transform transition-transform duration-300 lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

        {{-- Logo --}}
        <div class="h-20 flex items-center justify-between px-6 border-b border-gray-100">

            <a href="{{ route('owner.dashboard') }}"
               class="flex items-center gap-3 cursor-pointer">

                <div class="w-11 h-11 bg-emerald-600 rounded-2xl flex items-center justify-center text-white text-xl font-bold shadow-sm">
                    OS
                </div>

                <div>
                    <h1 class="font-bold text-lg text-gray-800 leading-tight">
                        OS Shop
                    </h1>

                    <p class="text-xs text-gray-400">
                        Shop Management
                    </p>
                </div>

            </a>

            <button type="button"
                    @click="sidebarOpen = false"
                    class="lg:hidden text-gray-500 hover:bg-gray-100 p-2 rounded-lg cursor-pointer">
                ✕
            </button>

        </div>

        {{-- Sidebar Menu --}}
        <div class="flex-1 overflow-y-auto px-4 py-5">

            {{-- Dashboard --}}
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">
                Dashboard
            </p>

            <a href="{{ route('owner.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl mb-2 transition cursor-pointer
               {{ request()->routeIs('owner.dashboard') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">

                <span>🏠</span>
                <span>ড্যাশবোর্ড</span>

            </a>


            {{-- Management --}}
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-7 mb-3">
                ব্যবস্থাপনা
            </p>


{{-- Sales --}}
<a href="{{ route('owner.sales.create') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-emerald-50 hover:text-emerald-700 transition">

    <svg xmlns="http://www.w3.org/2000/svg"
         width="20" height="20" viewBox="0 0 24 24"
         fill="none" stroke="currentColor"
         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="9" cy="21" r="1"></circle>
        <circle cx="20" cy="21" r="1"></circle>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
    </svg>

    <span>নতুন বিক্রি</span>
</a>

<a href="{{ route('owner.sales.index') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-emerald-50 hover:text-emerald-700 transition">

    <svg xmlns="http://www.w3.org/2000/svg"
         width="20" height="20" viewBox="0 0 24 24"
         fill="none" stroke="currentColor"
         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 3v18h18"></path>
        <path d="M8 16V8"></path>
        <path d="M13 16V5"></path>
        <path d="M18 16v-3"></path>
    </svg>

    <span>বিক্রির ইতিহাস</span>
</a>


            {{-- Products --}}
            <a href="{{ route('owner.shop-products.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl mb-1 transition cursor-pointer
               {{ request()->routeIs('owner.shop-products.index', 'owner.shop-products.show', 'owner.shop-products.purchase', 'owner.shop-products.purchase.store') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">

                <span>📦</span>
                <span>পণ্য</span>

            </a>

            {{-- Product Catalog --}}
            <a href="{{ route('owner.shop-products.catalog') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl mb-1 transition cursor-pointer
               {{ request()->routeIs('owner.shop-products.catalog') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">

                <span>➕</span>
                <span>পণ্য যোগ করুন</span>

            </a>

            {{-- Categories --}}
            <a href="{{ route('owner.categories.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl mb-1 transition cursor-pointer
               {{ request()->routeIs('owner.categories.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">

                <span>🏷️</span>
                <span>ক্যাটাগরি</span>

            </a>

            {{-- Units --}}
            <a href="{{ route('owner.units.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl mb-1 transition cursor-pointer
               {{ request()->routeIs('owner.units.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">

                <span>📏</span>
                <span>Unit</span>

            </a>

            {{-- Purchase --}}
<a href="{{ route('owner.purchases.index') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-emerald-50">
    <span>🛒</span>
    <span>ক্রয় / Purchase</span>
</a>

            {{-- Customers --}}
            <a href="#"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-gray-50 transition cursor-pointer opacity-70 mb-1">

                <span>👥</span>
                <span>কাস্টমার</span>
                <span class="ml-auto text-[10px] text-gray-400">Soon</span>

            </a>

            {{-- Suppliers --}}
            <a href="#"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-gray-50 transition cursor-pointer opacity-70 mb-1">

                <span>🏭</span>
                <span>সরবরাহকারী</span>
                <span class="ml-auto text-[10px] text-gray-400">Soon</span>

            </a>

            {{-- Returns --}}
            <a href="#"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-gray-50 transition cursor-pointer opacity-70 mb-1">

                <span>↩️</span>
                <span>ফেরত</span>
                <span class="ml-auto text-[10px] text-gray-400">Soon</span>

            </a>


            {{-- Accounts --}}
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-7 mb-3">
                হিসাব
            </p>

            @php
                $accountItems = [
                    ['icon' => '💰', 'name' => 'আয় ও ব্যয়'],
                    ['icon' => '📒', 'name' => 'হিসাবের খাতা'],
                    ['icon' => '🕒', 'name' => 'দৈনিক হিসাব'],
                    ['icon' => '📊', 'name' => 'রিপোর্ট'],
                ];
            @endphp

            @foreach($accountItems as $item)

                <a href="#"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-gray-50 transition cursor-pointer opacity-70 mb-1">

                    <span>{{ $item['icon'] }}</span>
                    <span>{{ $item['name'] }}</span>
                    <span class="ml-auto text-[10px] text-gray-400">Soon</span>

                </a>

            @endforeach


            {{-- Settings --}}
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-7 mb-3">
                অন্যান্য
            </p>

            <a href="#"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-gray-50 transition cursor-pointer opacity-70">

                <span>⚙️</span>
                <span>সেটিংস</span>
                <span class="ml-auto text-[10px] text-gray-400">Soon</span>

            </a>

        </div>


        {{-- Sidebar Bottom --}}
        <div class="p-4 border-t border-gray-100">

            <div class="bg-emerald-50 rounded-2xl p-4">

                <p class="text-sm font-semibold text-emerald-800">
                    OS Shop Management
                </p>

                <p class="text-xs text-emerald-600 mt-1">
                    আপনার দোকানের ব্যবস্থাপনা সহজ করুন।
                </p>

            </div>

        </div>

    </aside>


    {{-- Main Area --}}
    <div class="min-h-screen lg:ml-72 flex flex-col">


        {{-- Header --}}
        <header class="sticky top-0 z-30 bg-white border-b border-gray-200">

            <div class="h-16 px-4 md:px-6 flex items-center justify-between">

                {{-- Left --}}
                <div class="flex items-center gap-3">

                    <button type="button"
                            @click="sidebarOpen = true"
                            class="lg:hidden p-2 rounded-xl hover:bg-gray-100 text-gray-600 cursor-pointer">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-6 h-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>

                        </svg>

                    </button>

                    <div>
                        <p class="text-xs text-gray-400">
                            OS Shop Management
                        </p>

                        <h2 class="font-semibold text-gray-800">
                            @yield('page_heading', 'Dashboard')
                        </h2>
                    </div>

                </div>


                {{-- Right --}}
                <div class="flex items-center gap-3">

                    {{-- Profile Dropdown --}}
                    <div class="relative" @click.outside="profileOpen = false">

                        <button type="button"
                                @click="profileOpen = !profileOpen"
                                class="flex items-center gap-2 p-2 rounded-xl hover:bg-gray-50 cursor-pointer">

                            <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">

                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}

                            </div>

                            <div class="hidden sm:block text-left">

                                <p class="text-sm font-semibold text-gray-700">
                                    {{ auth()->user()->name ?? 'User' }}
                                </p>

                                <p class="text-xs text-gray-400">
                                    Account
                                </p>

                            </div>

                            <span class="text-gray-400 text-xs">
                                ▼
                            </span>

                        </button>


                        {{-- Dropdown --}}
                        <div x-show="profileOpen"
                             x-cloak
                             x-transition
                             style="display:none"
                             class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-2xl shadow-xl overflow-hidden">

                            <div class="p-4 border-b">

                                <p class="font-semibold text-gray-800">
                                    {{ auth()->user()->name ?? 'User' }}
                                </p>

                                <p class="text-xs text-gray-400 mt-1">
                                    {{ auth()->user()->email ?? '' }}
                                </p>

                            </div>

                            <div class="p-2">

                                <a href="{{ route('owner.dashboard') }}"
                                   class="block px-3 py-2 rounded-lg hover:bg-gray-50 text-sm cursor-pointer">
                                    Dashboard
                                </a>

                                <form method="POST"
                                      action="{{ route('logout') }}">

                                    @csrf

                                    <button type="submit"
                                            class="w-full text-left px-3 py-2 rounded-lg hover:bg-red-50 text-red-600 text-sm cursor-pointer">

                                        Logout

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </header>


        {{-- Content --}}
        <main class="flex-1 p-4 md:p-6 lg:p-8">

            @if(session('success'))
                <div class="mb-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')

        </main>


        {{-- Footer --}}
        <footer class="bg-white border-t border-gray-200 px-4 md:px-6 py-4">

            <div class="flex flex-col sm:flex-row justify-between items-center gap-2 text-xs text-gray-400">

                <p>
                    &copy; {{ date('Y') }} OS Shop Management
                </p>

                <p>
                    Shop Management System
                </p>

            </div>

        </footer>

    </div>

</div>

@yield('scripts')

</body>
</html>
