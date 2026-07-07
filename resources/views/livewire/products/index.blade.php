<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 flex items-center gap-3">
                <span class="bg-slate-100 text-slate-800 w-11 h-11 flex items-center justify-center rounded-2xl border border-slate-200/60 shadow-sm">
                    📦
                </span>
                بەڕێوەبردنی بەرهەمەکان
            </h1>
            <p class="text-slate-500 text-sm mt-1.5 font-medium">کۆنترۆڵکردنی سەرجەم کاڵاکان، نرخەکان، بارکۆد و ئاستی کۆگا.</p>
        </div>
        
        <button wire:click="openCreate" class="bg-slate-900 hover:bg-slate-800 active:bg-black text-white px-5 py-3 rounded-xl text-sm font-bold shadow-lg shadow-slate-900/10 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2.5 group">
            <svg class="w-5 h-5 text-slate-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
            </svg>
            زیادکردنی بەرهەم
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-4 mb-5 flex flex-col md:flex-row items-center gap-4">
        <div class="relative w-full md:w-80 group">
            <span class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400 group-focus-within:text-slate-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input wire:model.live.debounce.400ms="search" type="text" placeholder="گەڕان بە ناو یان بارکۆد..."
                   class="w-full bg-slate-50 border border-slate-200 focus:ring-4 focus:ring-slate-100 focus:border-slate-400 rounded-xl pr-10 pl-4 py-2.5 text-sm outline-none transition-all font-medium text-slate-800 placeholder:text-slate-400">
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right border-collapse">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-100 bg-slate-50/75 text-xs font-bold uppercase tracking-wider">
                        <th class="py-4 px-6 font-bold">ناوی بەرهەم</th>
                        <th class="py-4 px-6 font-bold">جۆر / پۆلێن</th>
                        <th class="py-4 px-6 font-bold">نرخی کڕین</th>
                        <th class="py-4 px-6 font-bold">نرخی فرۆشتن</th>
                        <th class="py-4 px-6 font-bold">بڕی بەردەست</th>
                        <th class="py-4 px-6 font-bold">دۆخی کۆگا</th>
                        <th class="py-4 px-6 font-bold text-left pl-10">کردارەکان</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/70">
                    @forelse($products as $p)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="py-4 px-6 font-bold text-slate-800">
                            {{ $p->name }}
                        </td>
                        
                        <td class="py-4 px-6">
                            <span class="px-2.5 py-1.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-xl border border-slate-200/40">
                                {{ $p->category->name ?? '-' }}
                            </span>
                        </td>
                        
                        <td class="py-4 px-6 font-bold text-slate-600 font-mono text-sm">
                            {{ number_format($p->purchase_price, 0) }} <span class="text-[10px] text-slate-400 font-normal">د.ع</span>
                        </td>
                        
                        <td class="py-4 px-6 font-extrabold text-indigo-600 font-mono text-sm">
                            {{ number_format($p->sale_price, 0) }} <span class="text-[10px] text-indigo-400 font-normal">د.ع</span>
                        </td>
                        
                        <td class="py-4 px-6 font-bold font-mono">
                            <span class="{{ $p->isLowStock() ? 'text-rose-600 bg-rose-50 px-2 py-1 rounded-lg' : 'text-slate-700' }}">
                                {{ $p->quantity }}
                            </span>
                        </td>
                        
                        <td class="py-4 px-6">
                            @if($p->isLowStock())
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold inline-flex items-center gap-1 bg-rose-50 text-rose-600 border border-rose-100/60">
                                    <span class="w-1 h-1 rounded-full bg-rose-500 animate-pulse"></span>
                                    کەمە
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold inline-flex items-center gap-1 bg-emerald-50 text-emerald-600 border border-emerald-100/60">
                                    <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                                    باشە
                                </span>
                            @endif
                        </td>
                        
                        <td class="py-4 px-6 text-left pl-6">
                            <div class="flex items-center justify-end gap-1.5">
                                <button wire:click="edit({{ $p->id }})" class="inline-flex items-center gap-1.5 px-3 py-2 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl text-xs font-bold transition-all border border-transparent hover:border-indigo-100">
                                    دەستکاری
                                </button>
                                <button wire:click="delete({{ $p->id }})" wire:confirm="دڵنیایت لە سڕینەوە؟" class="inline-flex items-center gap-1.5 px-3 py-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl text-xs font-bold transition-all border border-transparent hover:border-rose-100">
                                    سڕینەوە
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-16">
                            <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                <div class="w-16 h-16 bg-slate-50 border border-slate-100 flex items-center justify-center rounded-2xl text-2xl shadow-inner mb-4">
                                    📦
                                </div>
                                <span class="font-bold text-slate-700 text-base">هیچ بەرهەمێک نییە</span>
                                <p class="text-slate-400 text-xs mt-1 text-center px-4">هیچ کاڵایەک نەدۆزرایەوە، دڵنیابەوە لە ڕاستی پۆلێن یان وشەی گەڕانەکەت.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($products->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    @if($showModal)
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-md flex items-center justify-center z-50 p-4 transition-all duration-300" wire:click.self="$set('showModal', false)">
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden transform transition-all animate-[fade-in-up_0.2s_ease-out]">
            
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                <h2 class="font-extrabold text-slate-800 flex items-center gap-2.5 text-base">
                    {{ $editingId ? '✏️ دەستکاریکردنی بەرهەم' : '✨ زیادکردنی بەرهەمی نوێ' }}
                </h2>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 hover:bg-slate-200/60 p-1.5 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">ناوی بەرهەم <span class="text-rose-500">*</span></label>
                    <input wire:model="name" type="text" class="w-full bg-slate-50/50 border @error('name') border-rose-400 focus:ring-rose-100 focus:border-rose-400 @else border-slate-200 focus:ring-slate-100 focus:border-slate-900 @enderror focus:ring-4 rounded-xl px-4 py-2.5 text-sm outline-none transition-all font-semibold placeholder:text-slate-400 text-slate-800" placeholder="بۆ نموونە: کۆکا کۆلا 250مل">
                    @error('name') <span class="text-rose-500 text-xs font-semibold mt-1.5 block">⚠️ {{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">بارکۆد</label>
                        <input wire:model="barcode" type="text" class="w-full bg-slate-50/50 border border-slate-200 focus:ring-4 focus:ring-slate-100 focus:border-slate-900 rounded-xl px-4 py-2.5 text-sm outline-none transition-all font-mono placeholder:text-slate-300 text-slate-800" placeholder="قەد لێدانی ڕاستەوخۆ...">
                        @error('barcode') <span class="text-rose-500 text-xs font-semibold mt-1.5 block">⚠️ {{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">یەکە (Unit)</label>
                        <input wire:model="unit" type="text" class="w-full bg-slate-50/50 border border-slate-200 focus:ring-4 focus:ring-slate-100 focus:border-slate-900 rounded-xl px-4 py-2.5 text-sm outline-none transition-all font-semibold placeholder:text-slate-400 text-slate-800" placeholder="دانە، کارتۆن، کیلۆ...">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">جۆر / پۆلێن</label>
                        <select wire:model="category_id" class="w-full bg-slate-50/50 border border-slate-200 focus:ring-4 focus:ring-slate-100 focus:border-slate-900 rounded-xl px-4 py-2.5 text-sm outline-none transition-all font-semibold text-slate-800">
                            <option value="">هەڵبژێرە</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">دابینکەر</label>
                        <select wire:model="supplier_id" class="w-full bg-slate-50/50 border border-slate-200 focus:ring-4 focus:ring-slate-100 focus:border-slate-900 rounded-xl px-4 py-2.5 text-sm outline-none transition-all font-semibold text-slate-800">
                            <option value="">هەڵبژێرە</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">نرخی کڕین (د.ع)</label>
                        <input type="number" step="0.01" wire:model="purchase_price" class="w-full bg-slate-50/50 border border-slate-200 focus:ring-4 focus:ring-slate-100 focus:border-slate-900 rounded-xl px-4 py-2.5 text-sm outline-none transition-all font-mono font-bold text-slate-800" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">نرخی فرۆشتن (د.ع)</label>
                        <input type="number" step="0.01" wire:model="sale_price" class="w-full bg-slate-50/50 border border-slate-200 focus:ring-4 focus:ring-slate-100 focus:border-slate-900 rounded-xl px-4 py-2.5 text-sm outline-none transition-all font-mono font-bold text-slate-800" placeholder="0">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">بڕی بەردەست</label>
                        <input type="number" wire:model="quantity" class="w-full bg-slate-50/50 border border-slate-200 focus:ring-4 focus:ring-slate-100 focus:border-slate-900 rounded-xl px-4 py-2.5 text-sm outline-none transition-all font-mono font-bold text-slate-800" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">کەمترین ئاست (ئاگادارکردنەوە)</label>
                        <input type="number" wire:model="min_quantity" class="w-full bg-slate-50/50 border border-slate-200 focus:ring-4 focus:ring-slate-100 focus:border-slate-900 rounded-xl px-4 py-2.5 text-sm outline-none transition-all font-mono font-bold text-slate-800" placeholder="5">
                    </div>
                </div>
            </div>

            <div class="px-6 py-4.5 bg-slate-50/80 border-t border-slate-100 flex items-center justify-end gap-3">
                <button wire:click="$set('showModal', false)" class="px-4.5 py-2.5 rounded-xl text-sm font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 hover:text-slate-800 transition-all shadow-sm">
                    پاشگەزبوونەوە
                </button>
                <button wire:click="save" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 shadow-lg shadow-slate-900/10 active:scale-[0.98] transition-all flex items-center gap-2">
                    <span wire:loading.remove wire:target="save">پاشەکەوتکردن</span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        چاوەڕێ بکە...
                    </span>
                </button>
            </div>
            
        </div>
    </div>
    @endif
</div>