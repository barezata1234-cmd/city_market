<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 flex items-center gap-3">
                <span class="bg-gradient-to-br from-indigo-50 to-blue-50 text-indigo-600 w-12 h-12 flex items-center justify-center rounded-2xl border border-indigo-100/70 shadow-sm">
                    ✨
                </span>
                پۆلێنەکان و جۆرەکان
            </h1>
            <p class="text-slate-500 text-sm mt-1.5 font-medium">کۆنترۆڵ و ڕێکخستنی جۆرەکانی بەرهەم بە شێوازێکی خێرا.</p>
        </div>
        
        <button wire:click="openCreate" class="bg-slate-900 hover:bg-indigo-600 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-md hover:shadow-lg hover:shadow-indigo-600/20 transform hover:-translate-y-0.5 active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2 group">
            <svg class="w-5 h-5 text-slate-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
            </svg>
            زیادکردنی جۆری نوێ
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $c)
        <div class="bg-white rounded-2xl border border-slate-200/60 p-5 flex flex-col justify-between hover:shadow-xl hover:shadow-slate-200/40 hover:border-indigo-200/80 transition-all duration-300 group relative overflow-hidden">
            
            <div class="absolute top-0 right-0 left-0 h-[3px] bg-slate-100 group-hover:bg-indigo-500 transition-colors duration-300"></div>

            <div>
                <div class="flex items-start justify-between mb-4">
                    <div class="space-y-1">
                        <h3 class="font-black text-slate-800 text-base group-hover:text-indigo-600 transition-colors">{{ $c->name }}</h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">ناوی سەرەکی</p>
                    </div>
                    <span class="bg-slate-50 group-hover:bg-indigo-50 text-slate-600 group-hover:text-indigo-600 text-xs font-extrabold px-3 py-1.5 rounded-xl border border-slate-100 group-hover:border-indigo-100 transition-all">
                        ID: {{ $c->id }}
                    </span>
                </div>

                <div class="bg-slate-50/50 rounded-xl p-3 border border-slate-100 mb-4">
                    <span class="text-xs font-bold text-slate-400 block mb-0.5">ناوی کوردی:</span>
                    <span class="font-bold text-slate-700 text-sm">{{ $c->name_ku ?? 'دیاری نەکراوە' }}</span>
                </div>

                <p class="text-slate-500 text-xs leading-relaxed font-medium mb-6 line-clamp-2">
                    {{ $c->description ? $c->description : 'هیچ وردەکارییەک بۆ این جۆرە نەنووسراوە.' }}
                </p>
            </div>

            <div class="flex items-center gap-2 pt-4 border-t border-slate-100">
                <button wire:click="edit({{ $c->id }})" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-slate-50 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 rounded-xl text-xs font-bold transition-all border border-transparent hover:border-indigo-100">
                    <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    دەستکاری
                </button>
                <button wire:click="delete({{ $c->id }})" wire:confirm="دڵنیایت لە سڕینەوەی ئەم جۆرە؟" class="inline-flex items-center justify-center p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all border border-transparent hover:border-rose-100" title="سڕینەوە">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>

        </div>
        @empty
        <div class="col-span-1 sm:col-span-2 lg:col-span-3 bg-white rounded-2xl border border-dashed border-slate-300 py-16 text-center">
            <div class="w-16 h-16 bg-slate-50 border border-slate-100 flex items-center justify-center rounded-2xl text-2xl shadow-inner mb-4 mx-auto">
                📦
            </div>
            <span class="font-bold text-slate-700 text-base block">هیچ جۆرێک بوونی نییە</span>
            <p class="text-slate-400 text-xs mt-1 max-w-xs mx-auto px-4">تۆ هێشتا هیچ جۆرێکی بەرهەمت زیاد نەکردووە. لە دوگمەی سەرەوە دەتوانیت یەکەم جۆر زیاد بکەیت.</p>
        </div>
        @endforelse
    </div>

    @if($categories->hasPages())
        <div class="mt-8 p-4 bg-white rounded-2xl border border-slate-200/60 shadow-sm">
            {{ $categories->links() }}
        </div>
    @endif

    @if($showModal)
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-md flex items-center justify-center z-50 p-4 transition-all duration-300" wire:click.self="$set('showModal', false)">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl border border-slate-100 overflow-hidden transform transition-all animate-[fade-in-up_0.2s_ease-out]">
            
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                <h2 class="font-extrabold text-slate-800 flex items-center gap-2.5 text-base">
                    {{ $editingId ? '✏️ دەستکاریکردنی جۆر' : '✨ زیادکردنی جۆری نوێ' }}
                </h2>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 hover:bg-slate-200/60 p-1.5 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">ناوی ئینگلیزی/سەرەکی <span class="text-rose-500">*</span></label>
                    <input wire:model="name" type="text" class="w-full bg-slate-50/50 border @error('name') border-rose-400 focus:ring-rose-100 focus:border-rose-400 @else border-slate-200 focus:ring-indigo-100 focus:border-indigo-500 @enderror focus:ring-4 rounded-xl px-4 py-3 text-sm outline-none transition-all font-semibold placeholder:text-slate-400 text-slate-800" placeholder="بۆ نموونە: Drinks">
                    @error('name') <span class="text-rose-500 text-xs font-semibold mt-1.5 block flex items-center gap-1">⚠️ {{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">ناوی کوردی</label>
                    <input wire:model="name_ku" type="text" class="w-full bg-slate-50/50 border border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 rounded-xl px-4 py-3 text-sm outline-none transition-all font-semibold placeholder:text-slate-400 text-slate-800" placeholder="بۆ نموونە: خواردنەوەکان">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">وردەکاری (ئارەزوومەندانە)</label>
                    <textarea wire:model="description" rows="3" class="w-full bg-slate-50/50 border border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 rounded-xl px-4 py-3 text-sm outline-none transition-all resize-none font-medium placeholder:text-slate-400 text-slate-800" placeholder="کەمێک زانیاری لەسەر ئەم جۆرە بنووسە..."></textarea>
                </div>
            </div>

            <div class="px-6 py-4.5 bg-slate-50/80 border-t border-slate-100 flex items-center justify-end gap-3">
                <button wire:click="$set('showModal', false)" class="px-4.5 py-2.5 rounded-xl text-sm font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 hover:text-slate-800 transition-all shadow-sm">
                    پاشگەزبوونەوە
                </button>
                <button wire:click="save" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-600/10 active:scale-[0.98] transition-all flex items-center gap-2">
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