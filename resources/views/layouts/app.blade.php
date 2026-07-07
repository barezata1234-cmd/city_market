<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'ماركێتی شارەکەم' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
    <style>
        body { 
            font-family: 'Noto Sans Arabic', sans-serif; 
            background-color: #f8fafc;
        }
        
        /* شاردنەوەی سکڕۆڵبار بۆ لای تەنیشت بەڵام هێشتنەوەی کاراییەکەی */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
    </style>
</head>
<body class="text-slate-800 antialiased selection:bg-indigo-500 selection:text-white" x-data="{ mobileSidebarOpen: false }">
    
    <div class="flex h-screen overflow-hidden relative">
        
        <div 
            x-show="mobileSidebarOpen" 
            @click="mobileSidebarOpen = false" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden">
        </div>
        
        <aside 
            :class="mobileSidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
            class="w-72 bg-slate-900 flex flex-col shadow-2xl fixed lg:static inset-y-0 right-0 z-50 transform transition-transform duration-300 ease-in-out lg:flex-shrink-0">
            
            <div class="h-20 flex items-center justify-between lg:justify-center bg-slate-950/40 border-b border-slate-800/60 px-6 lg:px-4">
                <div class="text-white font-extrabold text-xl tracking-wide flex items-center gap-2">
                    <span class="bg-indigo-600 p-2 rounded-xl shadow-lg shadow-indigo-500/30">
                        🛒
                    </span>
                    ماركێتی شارەکەم
                </div>
                
                <button @click="mobileSidebarOpen = false" class="text-slate-400 hover:text-white lg:hidden p-1 rounded-lg hover:bg-slate-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto sidebar-scroll py-6 px-4 space-y-1.5 text-sm font-medium">
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                    <span class="text-lg">🏠</span> داشبۆرد
                </a>
                
                <a href="{{ route('sales.index') }}" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('sales.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                    <span class="text-lg">🛍️</span> فرۆشتن
                </a>
                
                <a href="{{ route('products.index') }}" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('products.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                    <span class="text-lg">📦</span> بەرهەمەکان
                </a>
                
                <a href="{{ route('categories.index') }}" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('categories.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                    <span class="text-lg">🏷️</span> جۆرەکان
                </a>
                
                <a href="{{ route('suppliers.index') }}" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('suppliers.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                    <span class="text-lg">🚚</span> دابینکەران
                </a>
                
                <a href="{{ route('purchases.index') }}" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('purchases.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                    <span class="text-lg">🧾</span> کڕینەکان
                </a>
                
                <a href="{{ route('customers.index') }}" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('customers.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                    <span class="text-lg">👥</span> شەریکەکان
                </a>
                
                <a href="{{ route('expenses.index') }}" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('expenses.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                    <span class="text-lg">💳</span> خەرجییەکان
                </a>
                
                <a href="{{ route('users.index') }}" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                    <span class="text-lg">🧑‍💼</span> بەکارهێنەران
                </a>
            </nav>

            <div class="p-4 border-t border-slate-800/60 bg-slate-900">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full flex items-center justify-center gap-2 px-4 py-3 text-red-400 hover:bg-red-500/10 hover:text-red-400 rounded-xl transition-all duration-200 text-sm font-bold border border-transparent hover:border-red-500/20">
                        <span>🚪</span> دەرچوون
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50 w-full">
            
            <header class="h-20 bg-white/70 backdrop-blur-md border-b border-slate-200 shadow-sm flex items-center justify-between px-4 md:px-8 shrink-0 z-30">
                
                <div class="flex items-center gap-3">
                    <button @click="mobileSidebarOpen = true" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <div class="flex items-center gap-2 bg-slate-100/80 px-3 py-1.5 md:px-4 md:py-2 rounded-full border border-slate-200">
                        <svg class="w-3.5 h-3.5 md:w-4 md:h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <div class="text-[10px] md:text-xs font-semibold text-slate-600 tracking-wider">
                            {{ now()->format('Y/m/d') }} <span class="text-slate-300 mx-0.5 md:mx-1">|</span> {{ now()->format('h:i A') }}
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-left hidden sm:block">
                        <div class="text-sm font-bold text-slate-700">{{ auth()->user()->name ?? 'بەڕێوەبەر' }}</div>
                        <div class="text-xs text-green-600 font-medium flex items-center justify-end gap-1">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                            پەیوەستە
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-600 to-blue-500 flex items-center justify-center text-white font-bold shadow-md border-2 border-white">
                        {{ mb_substr(auth()->user()->name ?? 'م', 0, 1) }}
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-4 md:p-8">
                
                @if (session('success'))
                    <div class="mb-6 bg-emerald-50 border-r-4 border-emerald-500 px-5 py-4 rounded-xl shadow-sm flex items-center gap-3 transform transition-all animate-[fade-in_0.5s_ease-out]">
                        <div class="bg-emerald-100 p-1.5 rounded-full">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-sm font-semibold text-emerald-800">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="animate-[fade-in-up_0.4s_ease-out]">
                    {{ $slot }}
                </div>

            </div>
        </main>
    </div>
    
    @livewireScripts
</body>
</html>