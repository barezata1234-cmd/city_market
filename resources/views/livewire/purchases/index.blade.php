<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 flex items-center gap-3">
                <span class="bg-slate-100 text-slate-800 w-11 h-11 flex items-center justify-center rounded-2xl border border-slate-200/60 shadow-sm">
                    🚚
                </span>
                ئەرشیفی کڕینەکان
            </h1>
            <p class="text-slate-500 text-sm mt-1.5 font-medium">بینین، بەدواداچوون و چاودێریکردنی سەرجەم پسوولەکانی کڕین و قەرزی دابینکەران.</p>
        </div>
        
        <a href="{{ route('purchases.create') }}" class="bg-slate-900 hover:bg-slate-800 active:bg-black text-white px-5 py-3 rounded-xl text-sm font-bold shadow-lg shadow-slate-900/10 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2.5 group">
            <svg class="w-5 h-5 text-slate-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
            </svg>
            کڕینی نوێ
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-4 mb-5 flex flex-col md:flex-row items-center gap-4">
        <div class="relative w-full md:w-80 group">
            <span class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400 group-focus-within:text-slate-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input wire:model.live.debounce.400ms="search" type="text" placeholder="گەڕان بە ژمارەی وەصڵ..."
                   class="w-full bg-slate-50 border border-slate-200 focus:ring-4 focus:ring-slate-100 focus:border-slate-400 rounded-xl pr-10 pl-4 py-2.5 text-sm outline-none transition-all font-medium text-slate-800 placeholder:text-slate-400">
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right border-collapse">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-100 bg-slate-50/75 text-xs font-bold uppercase tracking-wider">
                        <th class="py-4 px-6 font-bold">ژمارەی وەصڵ</th>
                        <th class="py-4 px-6 font-bold">دابینکەر</th>
                        <th class="py-4 px-6 font-bold">تۆمارکار</th>
                        <th class="py-4 px-6 font-bold">کۆی گشتی پسوولە</th>
                        <th class="py-4 px-6 font-bold">ماوە (قەرز)</th>
                        <th class="py-4 px-6 font-bold">دۆخی پارەدان</th>
                        <th class="py-4 px-6 font-bold text-left pl-6">بەروار و کات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/70">
                    @forelse($purchases as $purchase)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="py-4 px-6 font-black text-slate-900 font-mono text-sm">
                            #{{ $purchase->invoice_number }}
                        </td>
                        
                        <td class="py-4 px-6 font-bold text-slate-700">
                            {{ $purchase->supplier->name ?? '-' }}
                        </td>
                        
                        <td class="py-4 px-6 text-slate-600 font-medium">
                            {{ $purchase->user->name }}
                        </td>
                        
                        <td class="py-4 px-6 font-bold text-slate-800 font-mono">
                            {{ number_format($purchase->total, 0) }} <span class="text-[10px] text-slate-400 font-normal">د.ع</span>
                        </td>
                        
                        <td class="py-4 px-6 font-black font-mono text-sm">
                            @if($purchase->remaining > 0)
                                <span class="text-rose-600 bg-rose-50 px-2 py-1 rounded-lg border border-rose-100/50">
                                    {{ number_format($purchase->remaining, 0) }} <span class="text-[10px] font-normal">د.ع</span>
                                </span>
                            @else
                                <span class="text-slate-400 font-normal">-</span>
                            @endif
                        </td>
                        
                        <td class="py-4 px-6">
                            @if($purchase->status === 'paid')
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold inline-flex items-center gap-1 bg-emerald-50 text-emerald-600 border border-emerald-100/60">
                                    <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                                    دراوە
                                </span>
                            @define-route($purchase->status === 'partial')
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold inline-flex items-center gap-1 bg-amber-50 text-amber-600 border border-amber-100/60">
                                    <span class="w-1 h-1 rounded-full bg-amber-500 animate-pulse"></span>
                                    بەشێک دراوە
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold inline-flex items-center gap-1 bg-rose-50 text-rose-600 border border-rose-100/60">
                                    <span class="w-1 h-1 rounded-full bg-rose-500"></span>
                                    نەدراوە
                                </span>
                            @endif
                        </td>
                        
                        <td class="py-4 px-6 text-left pl-6 font-medium text-slate-400 font-mono text-xs" dir="ltr">
                            {{ $purchase->created_at->format('Y-m-d H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-16">
                            <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                <div class="w-16 h-16 bg-slate-50 border border-slate-100 flex items-center justify-center rounded-2xl text-2xl shadow-inner mb-4">
                                    🚚
                                </div>
                                <span class="font-bold text-slate-700 text-base">هیچ کڕینێک تۆمار نەکراوە</span>
                                <p class="text-slate-400 text-xs mt-1 text-center px-4">هیچ پسوولەیەکی کڕین لە سیستمەکەدا بوونی نییە یان وشەی گەڕانەکەت هەڵەیە.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($purchases->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>
</div>