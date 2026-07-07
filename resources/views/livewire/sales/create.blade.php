<div class="min-h-screen bg-slate-50/70 antialiased" dir="rtl" style="font-family: 'IBM Plex Sans Arabic', sans-serif;">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@500;700;900&family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 p-4 lg:p-6">

        {{-- پەیامی هەڵە (Error Alert) --}}
        @if (session('error'))
            <div class="xl:col-span-3 bg-rose-50 border border-rose-100 text-rose-700 px-5 py-4 rounded-2xl shadow-sm text-sm font-bold flex items-center gap-3 animate-pulse">
                <span class="text-xl">⚠️</span> {{ session('error') }}
            </div>
        @endif

        {{-- بەشی ڕاست: لیستی بەرهەمەکان --}}
        <div class="xl:col-span-2 flex flex-col space-y-5">

            {{-- بەشی گەڕان (Search Bar) --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-1 flex items-center gap-3 relative group shadow-sm focus-within:border-indigo-500 focus-within:ring-4 focus-within:ring-indigo-50 transition-all duration-200">
                <span class="absolute inset-y-0 right-0 flex items-center pr-5 pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input wire:model.live.debounce.100ms="search" autofocus
                       placeholder="بارکۆد سکان بکە یان لێرەدا بگەڕێ..."
                       class="w-full bg-transparent pr-12 pl-4 py-3.5 text-sm font-semibold text-slate-800 placeholder:text-slate-400 focus:outline-none">
            </div>

            {{-- گریدی کاڵاکان (Products Grid) --}}
            <div class="bg-white rounded-3xl border border-slate-100 p-6 flex-1 flex flex-col shadow-sm">
                <div class="flex items-center justify-between mb-5 pb-4 border-b border-dashed border-slate-100">
                    <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-600 animate-pulse"></span>
                        لیستی بەرهەمەکان
                    </h3>
                    <span class="text-xs font-semibold text-slate-400 bg-slate-50 px-3 py-1 rounded-md">کلیک لە کاڵاکە بکە بۆ زیادکردن</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 max-h-[68vh] overflow-y-auto pr-1 custom-scrollbar">
                    @foreach($products as $p)
                    <button wire:click="addToCart({{ $p->id }})"
                            class="relative border border-slate-100 rounded-2xl p-4 text-right bg-slate-50/50 hover:border-indigo-500 hover:bg-white hover:shadow-md active:translate-y-[1px] transition-all duration-200 flex flex-col justify-between h-40 {{ $p->quantity <= 0 ? 'opacity-40 grayscale pointer-events-none' : '' }}">

                        <div>
                            <div class="font-bold text-slate-700 text-sm line-clamp-2 leading-relaxed group-hover:text-indigo-600 transition-colors">{{ $p->name }}</div>
                            <div class="text-base font-extrabold text-emerald-600 mt-2 tracking-tight">{{ number_format($p->sale_price, 0) }} <span class="text-xs font-semibold text-emerald-400">د.ع</span></div>
                        </div>

                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-dashed border-slate-200/60 w-full">
                            <span class="text-[11px] font-bold px-2.5 py-1 rounded-lg {{ $p->quantity <= $p->min_quantity ? 'bg-rose-50 text-rose-600' : 'bg-slate-100 text-slate-600' }}">
                                مەوجود: {{ $p->quantity }}
                            </span>
                            <span class="w-7 h-7 rounded-full bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white flex items-center justify-center font-bold text-sm transition-colors">＋</span>
                        </div>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- بەشی چەپ: سەبەتە بە شێوەی وەسڵ (Receipt Dashboard) --}}
        <div class="relative bg-white rounded-3xl border border-slate-100 p-0 flex flex-col h-[calc(100vh-3rem)] sticky top-6 shadow-xl shadow-slate-100 overflow-hidden">
            
            <div class="flex justify-between px-2 bg-slate-50 py-1.5 border-b border-slate-100">
                @for($i = 0; $i < 16; $i++)
                    <span class="w-3 h-3 rounded-full bg-slate-200/70"></span>
                @endfor
            </div>

            <div class="px-6 pb-6 flex flex-col flex-1 min-h-0 pt-4">

                {{-- سەردێڕی وەصڵەکە --}}
                <div class="flex items-center justify-between pb-4 border-b border-dashed border-slate-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-lg shadow-inner">
                            🧾
                        </div>
                        <h2 class="font-bold text-slate-800 text-base">سەبەتەی فرۆشتن</h2>
                    </div>
                    <span class="bg-indigo-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-md shadow-indigo-500/20">
                        {{ count($cart) }} دانە
                    </span>
                </div>

                {{-- لیستی کاڵاکانی ناو سەبەتە --}}
                <div class="flex-1 overflow-y-auto my-4 space-y-2 pr-1 custom-scrollbar">
                    @forelse($cart as $productId => $item)
                    <div class="flex items-center justify-between p-3 bg-slate-50/50 rounded-2xl border border-slate-100 hover:bg-slate-50 transition-colors">
                        <div class="flex-1 min-w-0 ml-3">
                            <div class="text-xs font-bold text-slate-700 truncate">{{ $item['name'] }}</div>
                            <div class="text-xs font-extrabold text-emerald-600 mt-1">{{ number_format($item['price'], 0) }} <span class="text-[10px] font-normal text-emerald-400">د.ع</span></div>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="flex items-center border border-slate-200 rounded-xl bg-white overflow-hidden shadow-sm">
                                <input type="number" min="1" max="{{ $item['stock'] }}"
                                       wire:change="updateQty({{ $productId }}, $event.target.value)"
                                       value="{{ $item['qty'] }}"
                                       class="w-12 text-center text-xs font-bold py-1.5 focus:outline-none text-slate-700 bg-transparent">
                            </div>

                            <button wire:click="removeFromCart({{ $productId }})"
                                    class="text-slate-400 hover:text-rose-600 w-8 h-8 flex items-center justify-center rounded-xl hover:bg-rose-50 transition-all font-bold">
                                ✕
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center h-full text-slate-400 space-y-3 py-12">
                        <span class="text-5xl opacity-60">📭</span>
                        <p class="text-xs font-bold text-slate-500">سەبەتەکە ئێستا بەتاڵە</p>
                    </div>
                    @endforelse
                </div>

                {{-- فۆرمی کۆتایی و هەڵبژاردنەکان --}}
                <div class="pt-2 space-y-4 border-t border-slate-100">

                    <div>
                        <label class="text-xs font-bold text-slate-500 tracking-wider block mb-1.5">کڕیار</label>
                        <select wire:model="customer_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all">
                            <option value="">کڕیاری ئاسایی (نەناسراو)</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-bold text-slate-500 tracking-wider block mb-1.5">داشکاندن</label>
                            <input type="number" wire:model.live="discount" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-left font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all" placeholder="0">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 tracking-wider block mb-1.5">بڕی دراو</label>
                            <input type="number" wire:model="paid" class="w-full bg-white border-2 border-amber-400 rounded-xl px-3 py-2.5 text-sm text-left font-extrabold text-slate-800 focus:outline-none focus:ring-4 focus:ring-amber-100 transition-all" placeholder="0">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-500 tracking-wider block mb-2.5">شێوازی پارەدان</label>
                        <div class="grid grid-cols-3 gap-2.5">
                            <label class="border border-slate-200 rounded-xl p-3 text-center cursor-pointer text-xs font-bold block bg-slate-50/50 text-slate-600 hover:border-slate-300 hover:bg-slate-100/50 has-[:checked]:bg-emerald-600 has-[:checked]:text-white has-[:checked]:border-emerald-600 has-[:checked]:shadow-lg has-[:checked]:shadow-emerald-500/20 transition-all duration-200">
                                <input type="radio" wire:model="payment_method" value="cash" class="sr-only"> 
                                <span class="block text-base mb-1">💵</span> نەقد
                            </label>
                            <label class="border border-slate-200 rounded-xl p-3 text-center cursor-pointer text-xs font-bold block bg-slate-50/50 text-slate-600 hover:border-slate-300 hover:bg-slate-100/50 has-[:checked]:bg-indigo-600 has-[:checked]:text-white has-[:checked]:border-indigo-600 has-[:checked]:shadow-lg has-[:checked]:shadow-indigo-500/20 transition-all duration-200">
                                <input type="radio" wire:model="payment_method" value="card" class="sr-only"> 
                                <span class="block text-base mb-1">💳</span> کارت
                            </label>
                            <label class="border border-slate-200 rounded-xl p-3 text-center cursor-pointer text-xs font-bold block bg-slate-50/50 text-slate-600 hover:border-slate-300 hover:bg-slate-100/50 has-[:checked]:bg-amber-500 has-[:checked]:text-white has-[:checked]:border-amber-500 has-[:checked]:shadow-lg has-[:checked]:shadow-amber-500/20 transition-all duration-200">
                                <input type="radio" wire:model="payment_method" value="credit" class="sr-only"> 
                                <span class="block text-base mb-1">📝</span> قەرز
                            </label>
                        </div>
                    </div>

                    {{-- بەشی کۆی گشتی --}}
                    <div class="relative border-t border-dashed border-slate-200 pt-5 mt-4 flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-400 tracking-wider">کۆی گشتی</span>
                        <div class="text-3xl font-extrabold text-slate-800 tracking-tight">
                            {{ number_format($this->total, 0) }} <span class="text-sm font-semibold text-slate-400 mr-1">د.ع</span>
                        </div>
                    </div>

                    {{-- دوگمەی جێبەجێکردنی فرۆشتن --}}
                    <div class="mt-2">
                        <button wire:click="checkout" class="w-full bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 text-white rounded-xl py-4 text-sm font-bold shadow-xl shadow-indigo-500/20 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            تەواوکردن و چاپکردنی وەصڵ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- سکڕۆڵباری نوێکراوە --}}
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>