<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 flex items-center gap-3">
                <span class="bg-indigo-50 text-indigo-600 w-11 h-11 flex items-center justify-center rounded-2xl border border-indigo-100 shadow-sm text-xl">
                    🛒
                </span>
                ئەرشیفی فرۆشتنەکان
            </h1>
            <p class="text-slate-500 text-sm mt-1.5 font-medium">بینین و چاودێریکردنی سەرجەم وەصڵەکانی فرۆشتن و قەرزی کڕیاران.</p>
        </div>
        
        <a href="{{ route('sales.create') }}" class="bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white px-5 py-3 rounded-xl text-sm font-bold shadow-lg shadow-indigo-600/20 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2.5 group">
            <svg class="w-5 h-5 text-indigo-200 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
            </svg>
            فرۆشتنی نوێ
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-4 mb-6 flex flex-col md:flex-row items-center gap-4">
        
        <div class="relative w-full md:w-80 group">
            <span class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input wire:model.live.debounce.400ms="search" type="text" placeholder="گەڕان بە ژمارەی وەصڵ..."
                   class="w-full bg-slate-50 border border-slate-200 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-400 rounded-xl pr-10 pl-4 py-2.5 text-sm outline-none transition-all font-semibold text-slate-800 placeholder:text-slate-400">
        </div>

        <div class="w-full md:w-48 relative">
            <select wire:model.live="status" class="w-full bg-slate-50 border border-slate-200 focus:ring-4 focus:ring-indigo-50 focus:border-indigo-400 rounded-xl px-4 py-2.5 text-sm outline-none transition-all font-semibold text-slate-700 appearance-none cursor-pointer">
                <option value="">🔘 هەموو دۆخەکان</option>
                <option value="paid">✅ بەتەواوی دراوە</option>
                <option value="partial">⏳ بەشێک دراوە</option>
                <option value="unpaid">❌ نەدراوە (قەرز)</option>
            </select>
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right border-collapse">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-100 bg-slate-50/75 text-xs font-bold uppercase tracking-wider">
                        <th class="py-4 px-6 font-bold">ژمارەی وەصڵ</th>
                        <th class="py-4 px-6 font-bold">کڕیار</th>
                        <th class="py-4 px-6 font-bold">فرۆشیار</th>
                        <th class="py-4 px-6 font-bold">کۆی گشتی</th>
                        <th class="py-4 px-6 font-bold">ماوە (قەرز)</th>
                        <th class="py-4 px-6 font-bold">دۆخ</th>
                        <th class="py-4 px-6 font-bold text-left pl-6">بەروار و کات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/70">
                    @forelse($sales as $sale)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="py-4 px-6 font-black text-slate-900 font-mono text-sm">
                            #{{ $sale->invoice_number }}
                        </td>
                        
                        <td class="py-4 px-6 font-bold text-slate-700">
                            @if($sale->customer)
                                {{ $sale->customer->name }}
                            @else
                                <span class="text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md text-xs">کڕیاری گشتی</span>
                            @endif
                        </td>
                        
                        <td class="py-4 px-6 text-slate-500 font-medium text-xs">
                            {{ $sale->user->name }}
                        </td>
                        
                        <td class="py-4 px-6 font-bold text-slate-800 font-mono">
                            {{ number_format($sale->total, 0) }} <span class="text-[10px] text-slate-400 font-normal">د.ع</span>
                        </td>
                        
                        <td class="py-4 px-6 font-black font-mono text-sm">
                            @if($sale->remaining > 0)
                                <span class="text-rose-600 bg-rose-50 px-2 py-1 rounded-lg border border-rose-100/50">
                                    {{ number_format($sale->remaining, 0) }} <span class="text-[10px] font-normal">د.ع</span>
                                </span>
                            @else
                                <span class="text-slate-300 font-normal">-</span>
                            @endif
                        </td>
                        
                        <td class="py-4 px-6">
                            @if($sale->status === 'paid')
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 border border-emerald-100/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    دراوە
                                </span>
                            @define-route($sale->status === 'partial')
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold inline-flex items-center gap-1.5 bg-amber-50 text-amber-600 border border-amber-100/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    بەشێک دراوە
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold inline-flex items-center gap-1.5 bg-rose-50 text-rose-600 border border-rose-100/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    نەدراوە
                                </span>
                            @endif
                        </td>
                        
                        <td class="py-4 px-6 text-left pl-6 font-medium text-slate-400 font-mono text-xs" dir="ltr">
                            {{ $sale->created_at->format('Y-m-d H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-16">
                            <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                <div class="w-16 h-16 bg-slate-50 border border-slate-100 flex items-center justify-center rounded-3xl text-3xl shadow-inner mb-4">
                                    🧾
                                </div>
                                <span class="font-bold text-slate-700 text-base">هیچ فرۆشتنێک نەدۆزرایەوە</span>
                                <p class="text-slate-400 text-xs mt-1.5 text-center px-4 leading-relaxed">تا ئێستا هیچ وەصڵێکی فرۆشتن تۆمار نەکراوە یان گەڕانەکەت هیچ ئەنجامێکی نییە.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($sales->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $sales->links() }}
            </div>
        @endif
    </div>
</div>