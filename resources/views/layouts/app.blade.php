<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'ماركێتی شارەکەم' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Rabar+021&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
    <style>
        body { font-family: 'Noto Sans Arabic', sans-serif; background:#eef1f5; }
        .sidebar { background:#141a2b; }
        .sidebar a.active { background:#1f2a44; border-right:3px solid #22c55e; }
        .stat-card { border-radius:14px; }
    </style>
</head>
<body class="min-h-screen">
    <div class="flex min-h-screen">
        <aside class="sidebar w-64 shrink-0 text-gray-300 flex flex-col py-6 px-3">
            <div class="text-center mb-6">
                <div class="text-white font-bold text-lg">ماركێتی شارەکەم</div>
            </div>
            <nav class="flex-1 space-y-1 text-sm">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[#1f2a44] {{ request()->routeIs('dashboard') ? 'active' : '' }}">🏠 داشبۆرد</a>
                <a href="{{ route('sales.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[#1f2a44] {{ request()->routeIs('sales.*') ? 'active' : '' }}">🛒 فرۆشتن</a>
                <a href="{{ route('products.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[#1f2a44] {{ request()->routeIs('products.*') ? 'active' : '' }}">📦 بەرهەمەکان</a>
                <a href="{{ route('categories.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[#1f2a44] {{ request()->routeIs('categories.*') ? 'active' : '' }}">🏷️ جۆرەکان</a>
                <a href="{{ route('suppliers.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[#1f2a44] {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">🚚 دابینکەران</a>
                <a href="{{ route('purchases.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[#1f2a44] {{ request()->routeIs('purchases.*') ? 'active' : '' }}">🧾 کڕینەکان</a>
                <a href="{{ route('customers.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[#1f2a44] {{ request()->routeIs('customers.*') ? 'active' : '' }}">👥 شەریکەکان</a>
                <a href="{{ route('expenses.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[#1f2a44] {{ request()->routeIs('expenses.*') ? 'active' : '' }}">💳 خەرجییەکان</a>
                <a href="{{ route('users.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[#1f2a44] {{ request()->routeIs('users.*') ? 'active' : '' }}">🧑‍💼 بەکارهێنەران</a>
            </nav>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-right px-3 py-2 text-red-400 hover:bg-[#1f2a44] rounded-lg text-sm">🚪 دەرچوون</button>
            </form>
        </aside>

        <main class="flex-1 p-6">
            <div class="flex items-center justify-between mb-6 bg-white rounded-xl px-5 py-3 shadow-sm">
                <div class="text-sm text-gray-500">{{ now()->format('Y/m/d') }} | {{ now()->format('h:i A') }}</div>
                <div class="font-semibold text-gray-700">{{ auth()->user()->name ?? '' }}</div>
            </div>

            @if (session('success'))
                <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded-lg text-sm">{{ session('success') }}</div>
            @endif

            {{ $slot }}
        </main>
    </div>
    @livewireScripts
</body>
</html>