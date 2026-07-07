<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 flex items-center gap-3">
                <span class="bg-slate-100 text-slate-700 w-11 h-11 flex items-center justify-center rounded-2xl border border-slate-200/60 shadow-sm text-xl">
                    🚚
                </span>
                دابینکەران و کۆمپانیاکان
            </h1>
            <p class="text-slate-500 text-sm mt-1.5 font-medium">بەڕێوەبردنی زانیاری کۆمپانیاکان، بریکارەکان و چاودێریکردنی باڵانس و قەرزەکان.</p>
        </div>
        
        <button wire:click="openCreate" class="bg-slate-900 hover:bg-slate-800 active:bg-black text-white px-5 py-3 rounded-xl text-sm font-bold shadow-lg shadow-slate-900/10 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2.5 group">
            <svg class="w-5 h-5 text-slate-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
            </svg>
            زیادکردنی دابینکەر
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right border-collapse">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-100 bg-slate-50/75 text-xs font-bold uppercase tracking-wider">
                        <th class="py-4 px-6 font-bold">ناوی دابینکەر</th>
                        <th class="py-4 px-6 font-bold">مۆبایل</th>
                        <th class="py-4 px-6 font-bold">ئیمەیل</th>
                        <th class="py-4 px-6 font-bold">قەرز (باڵانس)</th>
                        <th class="py-4 px-6 font-bold text-center">کردارەکان</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/70">
                    @forelse($suppliers as $s)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="py-4 px-6 font-bold text-slate-800 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-black border border-slate-200">
                                {{ mb_substr($s->name, 0, 1) }}
                            </div>
                            {{ $s->name }}
                        </td>
                        
                        <td class="py-4 px-6 font-mono text-slate-600 font-medium text-xs">
                            {{ $s->phone ?? '-' }}
                        </td>
                        
                        <td class="py-4 px-6 text-slate-500 font-medium text-xs font-sans" dir="ltr">
                            {{ $s->email ?? '-' }}
                        </td>
                        
                        <td class="py-4 px-6 font-black font-mono text-sm">
                            @if($s->balance > 0)
                                <span class="text-rose-600 bg-rose-50 px-2.5 py-1.5 rounded-lg border border-rose-100/50 inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                    {{ number_format($s->balance, 0) }} <span class="text-[10px] font-normal">د.ع</span>
                                </span>
                            @else
                                <span class="text-emerald-600 bg-emerald-50 px-2.5 py-1.5 rounded-lg border border-emerald-100/50 text-xs inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    پاکتاوە
                                </span>
                            @endif
                        </td>
                        
                        <td class="py-4 px-6 text-center space-x-2 space-x-reverse opacity-40 group-hover:opacity-100 transition-opacity">
                            <button wire:click="edit({{ $s->id }})" class="bg-slate-100 hover:bg-indigo-50 text-slate-500 hover:text-indigo-600 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                ✏️ دەستکاری
                            </button>
                            <button wire:click="delete({{ $s->id }})" wire:confirm="ئایا دڵنیایت لە سڕینەوەی ئەم دابینکەرە؟ داتاکانی پەیوەست پێوەی لەدەست دەچن!" class="bg-slate-100 hover:bg-rose-50 text-slate-500 hover:text-rose-600 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                🗑️ سڕینەوە
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-16">
                            <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                <div class="w-16 h-16 bg-slate-50 border border-slate-100 flex items-center justify-center rounded-3xl text-3xl shadow-inner mb-4">
                                    🏭
                                </div>
                                <span class="font-bold text-slate-700 text-base">هیچ دابینکەرێک تۆمار نەکراوە</span>
                                <p class="text-slate-400 text-xs mt-1.5 text-center px-4 leading-relaxed">دەتوانیت لە ڕێگەی دوگمەی سەرەوە دابینکەر و کۆمپانیای نوێ بۆ سیستمەکە زیاد بکەیت.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($suppliers->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>

    @if($showModal)
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-all" wire:click.self="$set('showModal', false)">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-[scaleIn_0.2s_ease-out]">
            
            <div class="bg-slate-50 border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                <h2 class="font-black text-lg text-slate-800 flex items-center gap-2">
                    {{ $editingId ? '✏️ دەستکاریکردنی زانیاری' : '✨ زیادکردنی دابینکەری نوێ' }}
                </h2>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-rose-500 transition-colors w-8 h-8 flex items-center justify-center rounded-xl hover:bg-rose-50">
                    ✕
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1.5">ناوی کۆمپانیا / دابینکەر <span class="text-rose-500">*</span></label>
                    <input wire:model="name" type="text" class="w-full bg-slate-50 border border-slate-200 focus:ring-4 focus:ring-slate-100 focus:border-slate-400 rounded-xl px-4 py-2.5 text-sm outline-none transition-all font-semibold text-slate-800 placeholder:text-slate-400" placeholder="ناوی تەواو بنووسە...">
                    @error('name') <span class="text-rose-500 text-xs font-bold mt-1.5 block">⚠️ {{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1.5">ژمارەی مۆبایل</label>
                        <input wire:model="phone" type="text" dir="ltr" class="w-full bg-slate-50 border border-slate-200 focus:ring-4 focus:ring-slate-100 focus:border-slate-400 rounded-xl px-4 py-2.5 text-sm font-mono outline-none transition-all text-slate-800 text-left" placeholder="07...">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1.5">ئیمەیل</label>
                        <input wire:model="email" type="email" dir="ltr" class="w-full bg-slate-50 border border-slate-200 focus:ring-4 focus:ring-slate-100 focus:border-slate-400 rounded-xl px-4 py-2.5 text-sm font-sans outline-none transition-all text-slate-800 text-left" placeholder="email@example.com">
                    </div>
                </div>
                
                <div>
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1.5">ناونیشان</label>
                    <textarea wire:model="address" rows="3" class="w-full bg-slate-50 border border-slate-200 focus:ring-4 focus:ring-slate-100 focus:border-slate-400 rounded-xl px-4 py-2.5 text-sm outline-none transition-all font-medium text-slate-800 resize-none placeholder:text-slate-400" placeholder="ناونیشانی تەواوی شوێنەکە..."></textarea>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                <button wire:click="$set('showModal', false)" class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-200/50 transition-colors">
                    پاشگەزبوونەوە
                </button>
                <button wire:click="save" class="px-6 py-2.5 rounded-xl text-sm font-black bg-slate-900 hover:bg-slate-800 active:bg-black text-white shadow-lg shadow-slate-900/10 transition-all flex items-center gap-2">
                    پاشەکەوتکردن ✅
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
    </style>
    @endif
</div>