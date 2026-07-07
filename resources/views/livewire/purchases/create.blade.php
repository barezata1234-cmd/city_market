<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    @if (session('error'))
        <div class="lg:col-span-3 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-2 animate-pulse">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    {{-- بەشی لیستی بەرهەمەکان (Products List) --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5 flex flex-col">
        <div class="relative w-full group mb-4">
            <span class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400 group-focus-within:text-slate-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input wire:model.live.debounce.300ms="search" placeholder="گەڕان بۆ بەرهەم یان لێدانی بارکۆد..."
                   class="w-full bg-slate-50 border border-slate-200 focus:ring-4 focus:ring-slate-100 focus:border-slate-400 rounded-xl pr-10 pl-4 py-3 text-sm outline-none transition-all font-medium text-slate-800 placeholder:text-slate-400">
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 max-h-[68vh] overflow-y-auto ltr:pr-1 rtl:pl-1 custom-scrollbar">
            @foreach($products as $p)
            <button wire:click="addToCart({{ $p->id }})" class="border border-slate-200/70 bg-white hover:bg-slate-50 rounded-xl p-3.5 text-right hover:border-slate-800 hover:shadow-sm active:scale-[0.98] transition-all duration-150 flex flex-col justify-between h-28 group text-right">
                <div class="font-bold text-sm text-slate-800 line-clamp-2 group-hover:text-slate-900">{{ $p->name }}</div>
                
                <div class="mt-2 space-y-0.5">
                    <div class="text-[11px] text-slate-400 font-medium">
                        نرخی کڕین: <span class="font-mono font-bold text-slate-600">{{ number_format($p->purchase_price, 0) }}</span>
                    </div>
                    <div class="text-[11px] text-slate-400 font-medium flex items-center justify-between">
                        <span>کۆگا:</span>
                        <span class="font-mono px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $p->isLowStock() ? 'bg-rose-50 text-rose-600' : 'bg-slate-100 text-slate-600' }}">
                            {{ $p->quantity }}
                        </span>
                    </div>
                </div>
            </button>
            @endforeach
        </div>
    </div>

    {{-- بەشی سەبەتە و پسوولە (Cart Side) --}}
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5 flex flex-col h-[calc(68vh+7rem)]">
        <div class="font-black text-slate-900 text-base mb-4 flex items-center gap-2 pb-3 border-b border-slate-100">
            <span>🧾</span> وەصڵی کڕینی نوێ
        </div>
        
        <div class="flex-1 overflow-y-auto space-y-3 pr-0.5 custom-scrollbar">
            @forelse($cart as $productId => $item)
            <div class="bg-slate-50/50 border border-slate-100 rounded-xl p-3 hover:border-slate-200 transition-colors">
                <div class="text-xs font-bold text-slate-800 mb-2.5 truncate">{{ $item['name'] }}</div>
                
                <div class="flex items-center gap-2">
                    <div class="flex items-center bg-white border border-slate-200 rounded-lg px-2 py-1 focus-within:border-slate-400 transition-colors">
                        <span class="text-[10px] text-slate-400 font-bold ml-1">د.ع</span>
                        <input type="number" min="0" step="0.01" wire:change="updatePrice({{ $productId }}, $event.target.value)"
                               value="{{ $item['price'] }}" class="w-16 text-xs font-bold font-mono text-slate-800 outline-none text-center" title="نرخی کڕین">
                    </div>
                    
                    <span class="text-xs text-slate-300 font-bold">×</span>
                    
                    <input type="number" min="1" wire:change="updateQty({{ $productId }}, $event.target.value)"
                           value="{{ $item['qty'] }}" class="w-12 border border-slate-200 focus:border-slate-400 bg-white rounded-lg px-1 py-1 text-xs font-bold font-mono text-center text-slate-800 outline-none" title="بڕ">
                    
                    <button wire:click="removeFromCart({{ $productId }})" class="w-7 h-7 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center mr-auto transition-colors text-xs font-bold">
                        ✕
                    </button>
                </div>
            </div>
            @empty
            <div class="h-full flex flex-col items-center justify-center text-center py-12">
                <span class="text-3xl mb-2 opacity-40">🛒</span>
                <span class="font-bold text-slate-400 text-xs">سەبەتەکە بەتاڵە</span>
                <p class="text-[11px] text-slate-400 px-6 mt-0.5">کلیک لە کاڵاکانی لای ڕاست بکە بۆ زیادکردنیان</p>
            </div>
            @endforelse
        </div>

        <div class="mt-4 space-y-3 border-t border-slate-100 pt-4 bg-white">
            <div>
                <select wire:model="supplier_id" class="w-full bg-slate-50 border border-slate-200 focus:border-slate-400 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-700 outline-none transition-colors">
                    <option value="">👤 دابینکەر هەڵبژێرە</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-between text-sm bg-slate-50 rounded-xl p-3 border border-slate-100">
                <span class="font-bold text-slate-500">کۆی گشتی پسوولە:</span>
                <span class="font-black text-slate-900 font-mono text-base tracking-wide">
                    {{ number_format($this->total, 0) }} <span class="text-xs font-normal text-slate-500">د.ع</span>
                </span>
            </div>

            <div class="flex items-center justify-between p-1">
                <span class="text-xs font-bold text-slate-600">بڕی پارەی دراو:</span>
                <div class="flex items-center bg-white border border-slate-200 focus-within:border-slate-400 rounded-xl px-3 py-1.5 transition-colors">
                    <span class="text-xs text-slate-400 font-bold ml-1.5">د.ع</span>
                    <input type="number" wire:model="paid" class="w-24 text-sm font-black font-mono text-left text-slate-800 outline-none">
                </div>
            </div>

            <button wire:click="checkout" class="w-full bg-slate-900 hover:bg-slate-800 active:scale-[0.99] text-white rounded-xl py-3 text-sm font-bold shadow-md shadow-slate-950/10 transition-all flex items-center justify-center gap-2">
                <span>✅</span> تەواوکردنی فۆرمی کڕین
            </button>
        </div>
    </div>
</div>

<style>
    /* ستایلی نەرمکردنی سکرۆڵباری ناوخۆیی بۆ ڕوکارێکی جوانتر */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>