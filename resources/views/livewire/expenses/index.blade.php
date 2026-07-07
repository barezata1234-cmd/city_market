<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 flex items-center gap-3">
                <span class="bg-rose-50 text-rose-600 w-11 h-11 flex items-center justify-center rounded-2xl border border-rose-100/60 shadow-sm">
                    💳
                </span>
                بەڕێوەبردنی خەرجییەکان
            </h1>
            <p class="text-slate-500 text-sm mt-1.5 font-medium">چاودێریکردن، تۆمارکردن و پۆلێنکردنی سەرجەم خەرجییەکانی مارکێت.</p>
        </div>
        
        <button wire:click="openCreate" class="bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white px-5 py-3 rounded-xl text-sm font-bold shadow-lg shadow-rose-600/10 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2.5 group">
            <svg class="w-5 h-5 text-rose-200 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
            </svg>
            زیادکردنی خەرجی
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right border-collapse">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-100 bg-slate-50/75 text-xs font-bold uppercase tracking-wider">
                        <th class="py-4 px-6 font-bold">ناونیشان</th>
                        <th class="py-4 px-6 font-bold">بابەت / پۆلێن</th>
                        <th class="py-4 px-6 font-bold">بڕی خەرجی</th>
                        <th class="py-4 px-6 font-bold">بەروار</th>
                        <th class="py-4 px-6 font-bold">تۆمارکار</th>
                        <th class="py-4 px-6 font-bold text-left pl-10">کردارەکان</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/70">
                    @forelse($expenses as $e)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="py-4 px-6 font-bold text-slate-800">
                            {{ $e->title }}
                        </td>
                        
                        <td class="py-4 px-6">
                            <span class="px-2.5 py-1.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-xl border border-slate-200/40">
                                {{ $e->category ?: 'دیاری نەکراوە' }}
                            </span>
                        </td>
                        
                        <td class="py-4 px-6 font-black text-rose-600 font-mono text-base tracking-wide">
                            {{ number_format($e->amount, 0) }} <span class="text-xs text-rose-400 font-normal">د.ع</span>
                        </td>
                        
                        <td class="py-4 px-6 font-semibold text-slate-500 font-mono text-xs" dir="ltr" style="text-align: right;">
                            {{ $e->expense_date->format('Y-m-d') }}
                        </td>
                        
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-[10px] font-black uppercase border border-indigo-100 shadow-sm">
                                    {{ mb_substr($e->user->name ?? 'U', 0, 1) }}
                                </div>
                                <span class="text-slate-700 text-xs font-bold">{{ $e->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        
                        <td class="py-4 px-6 text-left pl-6">
                            <div class="flex items-center justify-end gap-1.5">
                                <button wire:click="edit({{ $e->id }})" class="inline-flex items-center gap-1.5 px-3 py-2 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl text-xs font-bold transition-all border border-transparent hover:border-indigo-100">
                                    <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    دەستکاری
                                </button>
                                <button wire:click="delete({{ $e->id }})" wire:confirm="دڵنیایت لە سڕینەوەی ئەم خەرجییە؟" class="inline-flex items-center gap-1.5 px-3 py-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl text-xs font-bold transition-all border border-transparent hover:border-rose-100">
                                    <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    سڕینەوە
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-16">
                            <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                <div class="w-16 h-16 bg-slate-50 border border-slate-100 flex items-center justify-center rounded-2xl text-2xl shadow-inner mb-4">
                                    💳
                                </div>
                                <span class="font-bold text-slate-700 text-base">هیچ خەرجییەک تۆمار نەکراوە</span>
                                <p class="text-slate-400 text-xs mt-1 text-center px-4">تۆ هێشتا هیچ خەرجییەکت بۆ ئەم بەشە زیاد نەکردووە. دەتوانیت ئێستا یەکەم خەرجی کلیل بکەیت.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($expenses->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>

    @if($showModal)
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-md flex items-center justify-center z-50 p-4 transition-all duration-300" wire:click.self="$set('showModal', false)">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl border border-slate-100 overflow-hidden transform transition-all animate-[fade-in-up_0.2s_ease-out]">
            
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                <h2 class="font-extrabold text-slate-800 flex items-center gap-2.5 text-base">
                    {{ $editingId ? '✏️ دەستکاریکردنی خەرجی' : '✨ تۆمارکردنی خەرجی نوێ' }}
                </h2>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 hover:bg-slate-200/60 p-1.5 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">ناونیشانی خەرجی <span class="text-rose-500">*</span></label>
                    <input wire:model="title" type="text" class="w-full bg-slate-50/50 border @error('title') border-rose-400 focus:ring-rose-100 focus:border-rose-400 @else border-slate-200 focus:ring-indigo-100 focus:border-indigo-500 @enderror focus:ring-4 rounded-xl px-4 py-3 text-sm outline-none transition-all font-semibold placeholder:text-slate-400 text-slate-800" placeholder="بۆ نموونە: کڕینی کەلوپەلی پاککەرەوە">
                    @error('title') <span class="text-rose-500 text-xs font-semibold mt-1.5 block flex items-center gap-1">⚠️ {{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">بڕی پارە (د.ع) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" wire:model="amount" class="w-full bg-slate-50/50 border @error('amount') border-rose-400 focus:ring-rose-100 focus:border-rose-400 @else border-slate-200 focus:ring-indigo-100 focus:border-indigo-500 @enderror focus:ring-4 rounded-xl px-4 py-3 text-sm outline-none transition-all font-mono font-bold placeholder:text-slate-300 text-slate-800" placeholder="0">
                        @error('amount') <span class="text-rose-500 text-xs font-semibold mt-1.5 block">⚠️ {{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">بابەت / پۆلێن</label>
                        <input wire:model="category" type="text" class="w-full bg-slate-50/50 border border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 rounded-xl px-4 py-3 text-sm outline-none transition-all font-semibold placeholder:text-slate-400 text-slate-800" placeholder="کرێ، کارەبا، مووچە...">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">بەرواری خەرجکردن</label>
                    <input type="date" wire:model="expense_date" class="w-full bg-slate-50/50 border border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 rounded-xl px-4 py-3 text-sm outline-none transition-all font-mono font-semibold text-right text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">تێبینی یان وردەکاری زیاتر</label>
                    <textarea wire:model="note" rows="2" class="w-full bg-slate-50/50 border border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 rounded-xl px-4 py-2.5 text-sm outline-none transition-all resize-none font-medium placeholder:text-slate-400 text-slate-800" placeholder="وردەکاری زیاتر لێرە بنووسە..."></textarea>
                </div>
            </div>

            <div class="px-6 py-4.5 bg-slate-50/80 border-t border-slate-100 flex items-center justify-end gap-3">
                <button wire:click="$set('showModal', false)" class="px-4.5 py-2.5 rounded-xl text-sm font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 hover:text-slate-800 transition-all shadow-sm">
                    پاشگەزبوونەوە
                </button>
                <button wire:click="save" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-lg shadow-rose-600/10 active:scale-[0.98] transition-all flex items-center gap-2">
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