<!doctype html>
<html lang="en" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/site.css', 'resources/js/site.js'])
</head>
<body class="min-h-screen bg-gradient-to-b from-[#f2f8fd] via-[#f7fbff] to-white text-gray-900">
    <header class="border-b border-primary/20 bg-[#edf5fc]">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
            <a href="/" class="text-lg font-semibold text-primary">{{ config('app.name') }}</a>
            <nav class="flex items-center gap-4 text-sm">
                <a href="/products" class="hover:text-primary">Products</a>
                <a href="{{ route('cart.show') }}" class="hover:text-primary">Cart</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-10">
        @if (session('status'))
            <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <ul class="grid gap-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
