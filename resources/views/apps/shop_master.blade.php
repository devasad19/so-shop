
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

<body class="min-h-screen bg-white text-gray-800 antialiased">

    @yield('content')

    @stack('scripts')

</body>
</html>
