<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 flex items-center gap-3">
                <span class="bg-purple-50 text-purple-600 w-11 h-11 flex items-center justify-center rounded-2xl border border-purple-100/60 shadow-sm">
                    👥
                </span>
                بەڕێوەبردنی شەریکەکان
            </h1>
            <p class="text-slate-500 text-sm mt-1.5 font-medium">کۆنترۆڵکردنی زانیاری شەریکەکان، ناونیشان و دۆخی داراییان.</p>
        </div>
        
        <button wire:click="openCreate" class="bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white px-5 py-3 rounded-xl text-sm font-bold shadow-lg shadow-indigo-600/20 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2.5 group">
            <svg class="w-5 h-5 text-indigo-200 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
            </svg>
            زیادکردنی شەریک
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right border-collapse">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-100 bg-slate-50/75 text-xs font-bold uppercase tracking-wider">
                        <th class="py-4 px-6 font-bold">ناو</th>
                        <th class="py-4 px-6 font-bold">مۆبایل</th>
                        <th class="py-4 px-6 font-bold">ئیمەیل</th>
                        <th class="py-4 px-6 font-bold">قەرز</th>
                        <th class="py-4 px-6 font-bold text-left pl-10">کردارەکان</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/70">
                    @forelse($customers as $c)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="py-4 px-6 font-bold text-slate-800">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-black text-xs border border-purple-100 shadow-sm group-hover:scale-105 transition-transform">
                                    {{ mb_substr($c->name, 0, 1) }}
                                </div>
                                <span class="tracking-wide">{{ $c->name }}</span>
                            </div>
                        </td>
                        
                        <td class="py-4 px-6 font-semibold text-slate-600 font-mono tracking-wide" dir="ltr" style="text-align: right;">
                            {{ $c->phone ?: '---' }}
                        </td>
                        
                        <td class="py-4 px-6 font-medium text-slate-400 font-mono text-xs" dir="ltr" style="text-align: right;">
                            {{ $c->email ?: '---' }}
                        </td>
                        
                        <td class="py-4 px-6">
                            <span class="px-3 py-1.5 rounded-xl text-xs font-bold inline-flex items-center gap-1.5 {{ $c->balance > 0 ? 'bg-rose-50 text-rose-600 border border-rose-100/80' : 'bg-emerald-50 text-emerald-600 border border-emerald-100/80' }}">
                                @if($c->balance > 0)
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                @else
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                @endif
                                <span class="font-mono">{{ number_format($c->balance, 0) }}</span> د.ع
                            </span>
                        </td>
                        
                        <td class="py-4 px-6 text-left pl-6">
                            <div class="flex items-center justify-end gap-1.5">
                                <button wire:click="edit({{ $c->id }})" class="inline-flex items-center gap-1.5 px-3 py-2 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl text-xs font-bold transition-all border border-transparent hover:border-indigo-100">
                                    <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    دەستکاری
                                </button>
                                <button wire:click="delete({{ $c->id }})" wire:confirm="دڵنیایت لە سڕینەوەی ئەم شەریکە؟" class="inline-flex items-center gap-1.5 px-3 py-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl text-xs font-bold transition-all border border-transparent hover:border-rose-100">
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
                        <td colspan="5" class="text-center py-16">
                            <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                <div class="w-16 h-16 bg-slate-50 border border-slate-100 flex items-center justify-center rounded-2xl text-2xl shadow-inner mb-4">
                                    👥
                                </div>
                                <span class="font-bold text-slate-700 text-base">هیچ شەریکێک تۆمار نەکراوە</span>
                                <p class="text-slate-400 text-xs mt-1 text-center px-4">لیستی شەریکەکان چۆڵە. تۆ دەتوانیت لە ڕێگەی دوگمەی سەرەوە یەکەم شەریک کلیل بکەیت.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($customers->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

    @if($showModal)
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-md flex items-center justify-center z-50 p-4 transition-all duration-300" wire:click.self="$set('showModal', false)">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl border border-slate-100 overflow-hidden transform transition-all animate-[fade-in-up_0.2s_ease-out]">
            
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                <h2 class="font-extrabold text-slate-800 flex items-center gap-2.5 text-base">
                    {{ $editingId ? '✏️ دەستکاریکردنی زانیاری شەریک' : '✨ زیادکردنی شەریکی نوێ' }}
                </h2>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 hover:bg-slate-200/60 p-1.5 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">ناو <span class="text-rose-500">*</span></label>
                    <input wire:model="name" type="text" class="w-full bg-slate-50/50 border @error('name') border-rose-400 focus:ring-rose-100 focus:border-rose-400 @else border-slate-200 focus:ring-indigo-100 focus:border-indigo-500 @enderror focus:ring-4 rounded-xl px-4 py-3 text-sm outline-none transition-all font-semibold placeholder:text-slate-400 text-slate-800" placeholder="ناوی تەواوی شەریک...">
                    @error('name') <span class="text-rose-500 text-xs font-semibold mt-1.5 block flex items-center gap-1">⚠️ {{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">مۆبایل</label>
                    <input wire:model="phone" type="text" dir="ltr" class="w-full bg-slate-50/50 border border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 rounded-xl px-4 py-3 text-sm outline-none transition-all font-mono tracking-wider text-right placeholder:text-slate-300 text-slate-800" placeholder="07XX XXX XXXX">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">ئیمەیل</label>
                    <input wire:model="email" type="email" dir="ltr" class="w-full bg-slate-50/50 border border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 rounded-xl px-4 py-3 text-sm outline-none transition-all font-mono text-right placeholder:text-slate-300 text-slate-800" placeholder="example@email.com">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">ناونیشان</label>
                    <textarea wire:model="address" rows="2" class="w-full bg-slate-50/50 border border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 rounded-xl px-4 py-2.5 text-sm outline-none transition-all resize-none font-medium placeholder:text-slate-400 text-slate-800" placeholder="ناونیشانی نیشتەجێبوون یان ئۆفیسی سەرەکی..."></textarea>
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