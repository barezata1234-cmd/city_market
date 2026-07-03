<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 p-4 bg-gray-50 min-h-screen antialiased text-gray-800" dir="rtl">
    
    {{-- Error Flash Messages --}}
    @if (session('error'))
        <div class="xl:col-span-3 bg-red-50 border-r-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm text-sm font-medium flex items-center gap-2 animate-pulse">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    {{-- Left Side: Products Grid (Takes 2/3 of space on large screens) --}}
    <div class="xl:col-span-2 flex flex-col space-y-4">
        
        {{-- Search Bar Section --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex items-center gap-3">
            <div class="w-full relative">
                <input wire:model.live.debounce.100ms="search" autofocus
                       placeholder="🔍 بارکۆد سکان بکە یان لێرەدا بگەڕێ بۆ بەرهەم..."
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl pr-4 pl-10 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
            </div>
        </div>

        {{-- Products Grid --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex-1">
            <h3 class="text-sm font-bold text-gray-400 mb-4 uppercase tracking-wider">لیستی بەرهەمەکان</h3>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 max-h-[70vh] overflow-y-auto pr-1">
                @foreach($products as $p)
                <button wire:click="addToCart({{ $p->id }})"
                        class="group relative border border-gray-100 rounded-xl p-4 text-right bg-white hover:bg-blue-50/30 hover:border-blue-400 transition-all duration-200 shadow-sm hover:shadow-md flex flex-col justify-between h-36 {{ $p->quantity <= 0 ? 'opacity-40 pointer-events-none bg-gray-50' : '' }}">
                    
                    <div>
                        <div class="font-bold text-gray-800 text-sm group-hover:text-blue-600 transition-colors line-clamp-2">{{ $p->name }}</div>
                        <div class="text-xs text-gray-400 mt-1 font-semibold">{{ number_format($p->sale_price, 0) }} د.ع</div>
                    </div>

                    <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-50 w-full">
                        <span class="text-[11px] font-medium px-2 py-0.5 rounded-full {{ $p->quantity <= $p->min_quantity ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }}">
                            مەوجود: {{ $p->quantity }}
                        </span>
                        <span class="opacity-0 group-hover:opacity-100 text-blue-500 text-xs font-bold transition-opacity">
                            ＋ زیادکردن
                        </span>
                    </div>
                </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right Side: Cart & Checkout (Takes 1/3 of space) --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-5 flex flex-col h-[calc(100vh-2rem)] sticky top-4">
        
        {{-- Cart Header --}}
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <span class="text-lg">🛒</span>
                <h2 class="font-black text-gray-800 text-base">سەبەتەی کڕین</h2>
            </div>
            <span class="bg-gray-100 text-gray-700 text-xs font-bold px-2.5 py-1 rounded-full">
                {{ count($cart) }} بابەت
            </span>
        </div>

        {{-- Cart Items List --}}
        <div class="flex-1 overflow-y-auto my-4 space-y-3 pr-1">
            @forelse($cart as $productId => $item)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100 hover:border-gray-200 transition-all">
                <div class="flex-1 min-w-0 ml-3">
                    <div class="text-sm font-bold text-gray-800 truncate">{{ $item['name'] }}</div>
                    <div class="text-xs text-blue-600 font-semibold mt-0.5">{{ number_format($item['price'], 0) }} د.ع</div>
                </div>
                
                <div class="flex items-center gap-3">
                    {{-- Quantity Input --}}
                    <div class="flex items-center border border-gray-200 rounded-lg bg-white overflow-hidden shadow-sm">
                        <input type="number" min="1" max="{{ $item['stock'] }}" 
                               wire:change="updateQty({{ $productId }}, $event.target.value)"
                               value="{{ $item['qty'] }}" 
                               class="w-12 text-center text-xs font-bold py-1 focus:outline-none bg-transparent">
                    </div>
                    
                    {{-- Remove Button --}}
                    <button wire:click="removeFromCart({{ $productId }})" 
                            class="text-gray-400 hover:text-red-500 p-1.5 rounded-lg hover:bg-red-50 transition-colors">
                        🗑️
                    </button>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-16 text-gray-400 space-y-2">
                <span class="text-4xl">📥</span>
                <p class="text-sm font-medium">سەبەتەکە ئێستا بەتاڵە</p>
            </div>
            @endforelse
        </div>

        {{-- Checkout Details Form --}}
        <div class="pt-4 border-t border-gray-100 space-y-3 bg-white">
            
            {{-- Customer Selection --}}
            <div>
                <label class="text-[11px] font-bold text-gray-400 uppercase block mb-1">کڕیار</label>
                <select wire:model="customer_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">کڕیاری ئاسایی (گشتی)</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Discount and Paid Row --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[11px] font-bold text-gray-400 uppercase block mb-1">داشکاندن (د.ع)</label>
                    <input type="number" wire:model.live="discount" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm text-left font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-gray-400 uppercase block mb-1">بڕی دراو (د.ع)</label>
                    <input type="number" wire:model="paid" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm text-left font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            {{-- Payment Method --}}
            <div>
                <label class="text-[11px] font-bold text-gray-400 uppercase block mb-1">شێوازی پارەدان</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="border border-gray-200 rounded-xl p-2 text-center cursor-pointer text-xs font-bold block bg-gray-50 has-[:checked]:bg-blue-600 has-[:checked]:text-white has-[:checked]:border-blue-600 transition-all">
                        <input type="radio" wire:model="payment_method" value="cash" class="sr-only"> نەقد
                    </label>
                    <label class="border border-gray-200 rounded-xl p-2 text-center cursor-pointer text-xs font-bold block bg-gray-50 has-[:checked]:bg-blue-600 has-[:checked]:text-white has-[:checked]:border-blue-600 transition-all">
                        <input type="radio" wire:model="payment_method" value="card" class="sr-only"> کارت
                    </label>
                    <label class="border border-gray-200 rounded-xl p-2 text-center cursor-pointer text-xs font-bold block bg-gray-50 has-[:checked]:bg-blue-600 has-[:checked]:text-white has-[:checked]:border-blue-600 transition-all">
                        <input type="radio" wire:model="payment_method" value="credit" class="sr-only"> قەرز
                    </label>
                </div>
            </div>

            {{-- Total Summary --}}
            <div class="bg-gray-900 text-white rounded-xl p-4 mt-2 shadow-sm flex items-center justify-between">
                <span class="text-xs font-bold text-gray-400">کۆی گشتی بۆ پارەدان:</span>
                <span class="text-xl font-black text-emerald-400">{{ number_format($this->total, 0) }} <span class="text-xs font-normal">د.ع</span></span>
            </div>

            {{-- Submit Checkout Button --}}
            <button wire:click="checkout" class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-xl py-3 text-sm font-black mt-2 shadow-md hover:shadow-lg transition-all duration-150 flex items-center justify-center gap-2">
                <span>⚡</span> تەواوکردنی فرۆشتن و پرینت
            </button>
        </div>
    </div>
</div>