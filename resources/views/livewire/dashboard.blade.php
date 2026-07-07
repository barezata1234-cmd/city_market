<div>
    <div class="bg-gradient-to-r from-indigo-600 to-blue-500 rounded-3xl px-8 py-8 mb-8 text-white shadow-lg shadow-indigo-500/30 flex flex-col md:flex-row md:items-center justify-between relative overflow-hidden">
        <div class="absolute -left-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute right-20 -bottom-10 w-32 h-32 bg-indigo-900/20 rounded-full blur-xl"></div>
        
        <div class="relative z-10 mb-4 md:mb-0">
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight mb-2">بەخێرهاتیت، {{ auth()->user()->name ?? 'بەڕێوەبەر' }} 👋</h1>
            <p class="text-indigo-100 font-medium text-sm">{{ now()->translatedFormat('l') }} | {{ now()->format('Y/m/d - H:i') }}</p>
        </div>
        <div class="relative z-10 self-start md:self-center bg-white/20 backdrop-blur-md px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm border border-white/20">
            ماركێتی شارەکەم
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 md:gap-5 mb-8">
        
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition-all duration-200">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl mb-4 shadow-inner">📦</div>
            <div class="text-xs font-semibold text-slate-500 mb-1">کۆی بەرهەمەکان</div>
            <div class="text-2xl font-bold text-slate-800">{{ $stats['products_count'] }}</div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition-all duration-200">
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl mb-4 shadow-inner">💳</div>
            <div class="text-xs font-semibold text-slate-500 mb-1">خەرجی ئەمڕۆ</div>
            <div class="text-xl font-bold text-rose-600">{{ number_format($stats['today_expenses'], 0) }}<span class="text-xs ml-1 text-rose-400">د.ع</span></div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition-all duration-200">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl mb-4 shadow-inner">🛒</div>
            <div class="text-xs font-semibold text-slate-500 mb-1">ژمارەی فرۆشتن</div>
            <div class="text-2xl font-bold text-slate-800">{{ $stats['today_sales_count'] }}</div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition-all duration-200">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl mb-4 shadow-inner">🔄</div>
            <div class="text-xs font-semibold text-slate-500 mb-1">قەرزی ئەمڕۆ</div>
            <div class="text-xl font-bold text-amber-600">{{ number_format($stats['today_debt'], 0) }}<span class="text-xs ml-1 text-amber-400">د.ع</span></div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition-all duration-200">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4 shadow-inner">🧾</div>
            <div class="text-xs font-semibold text-slate-500 mb-1">پارەی فرۆشتن</div>
            <div class="text-xl font-bold text-emerald-600">{{ number_format($stats['today_sales_total'], 0) }}<span class="text-xs ml-1 text-emerald-400">د.ع</span></div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition-all duration-200">
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center text-2xl mb-4 shadow-inner animate-pulse">⚠️</div>
            <div class="text-xs font-semibold text-slate-500 mb-1">کەمی کۆگا</div>
            <div class="text-2xl font-bold text-orange-500">{{ $stats['low_stock'] }}</div>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        
        <a href="{{ route('sales.create') }}" class="group bg-white rounded-2xl p-6 text-center shadow-sm border border-slate-100 hover:shadow-md hover:border-indigo-200 transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-14 h-14 mx-auto rounded-full bg-indigo-50 flex items-center justify-center text-2xl mb-3 group-hover:bg-indigo-600 group-hover:scale-110 transition-all duration-300">🛒</div>
            <div class="font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">فرۆشتن</div>
            <div class="text-xs text-slate-400 mt-1.5">فرۆشتن و وەصڵ دروستکردن</div>
        </a>

        <a href="{{ route('purchases.create') }}" class="group bg-white rounded-2xl p-6 text-center shadow-sm border border-slate-100 hover:shadow-md hover:border-blue-200 transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-14 h-14 mx-auto rounded-full bg-blue-50 flex items-center justify-center text-2xl mb-3 group-hover:bg-blue-600 group-hover:scale-110 transition-all duration-300">🧾</div>
            <div class="font-bold text-slate-700 group-hover:text-blue-600 transition-colors">کڕین</div>
            <div class="text-xs text-slate-400 mt-1.5">کڕین لە دابینکەرەوە</div>
        </a>

        <a href="{{ route('products.index') }}" class="group bg-white rounded-2xl p-6 text-center shadow-sm border border-slate-100 hover:shadow-md hover:border-emerald-200 transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-14 h-14 mx-auto rounded-full bg-emerald-50 flex items-center justify-center text-2xl mb-3 group-hover:bg-emerald-500 group-hover:scale-110 transition-all duration-300">📦</div>
            <div class="font-bold text-slate-700 group-hover:text-emerald-600 transition-colors">بەرهەمەکان</div>
            <div class="text-xs text-slate-400 mt-1.5">بەڕێوەبردنی بەرهەمەکان</div>
        </a>

        <a href="{{ route('categories.index') }}" class="group bg-white rounded-2xl p-6 text-center shadow-sm border border-slate-100 hover:shadow-md hover:border-amber-200 transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-14 h-14 mx-auto rounded-full bg-amber-50 flex items-center justify-center text-2xl mb-3 group-hover:bg-amber-500 group-hover:scale-110 transition-all duration-300">🏷️</div>
            <div class="font-bold text-slate-700 group-hover:text-amber-600 transition-colors">جۆرەکان</div>
            <div class="text-xs text-slate-400 mt-1.5">بەڕێوەبردنی جۆرەکان</div>
        </a>

        <a href="{{ route('customers.index') }}" class="group bg-white rounded-2xl p-6 text-center shadow-sm border border-slate-100 hover:shadow-md hover:border-purple-200 transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-14 h-14 mx-auto rounded-full bg-purple-50 flex items-center justify-center text-2xl mb-3 group-hover:bg-purple-600 group-hover:scale-110 transition-all duration-300">👥</div>
            <div class="font-bold text-slate-700 group-hover:text-purple-600 transition-colors">وەصڵەکان</div>
            <div class="text-xs text-slate-400 mt-1.5">بینین و چاپکردنی وەصڵ</div>
        </a>

    </div>

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-lg text-slate-800">دوایین فرۆشتنەکان</h3>
            <a href="{{ route('sales.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold bg-indigo-50 px-4 py-1.5 rounded-lg transition-colors">بینینی هەمووی ←</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-100 bg-slate-50/50">
                        <th class="py-3 px-4 font-semibold rounded-tr-xl">ژمارەی وەصڵ</th>
                        <th class="py-3 px-4 font-semibold">کڕیار</th>
                        <th class="py-3 px-4 font-semibold">فرۆشیار</th>
                        <th class="py-3 px-4 font-semibold">کۆی گشتی</th>
                        <th class="py-3 px-4 font-semibold rounded-tl-xl">دۆخ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSales as $sale)
                    <tr class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors group">
                        <td class="py-3 px-4 font-medium text-slate-700">#{{ $sale->invoice_number }}</td>
                        <td class="py-3 px-4 text-slate-600">{{ $sale->customer->name ?? 'کڕیاری ئاسایی' }}</td>
                        <td class="py-3 px-4 text-slate-600">{{ $sale->user->name }}</td>
                        <td class="py-3 px-4 font-bold text-slate-700">{{ number_format($sale->total, 0) }} <span class="text-xs text-slate-400 font-normal">د.ع</span></td>
                        <td class="py-3 px-4">
                            <span class="px-3 py-1.5 rounded-lg text-xs font-bold inline-flex items-center gap-1
                                {{ $sale->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : ($sale->status === 'partial' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $sale->status === 'paid' ? 'bg-emerald-500' : ($sale->status === 'partial' ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                                {{ $sale->status === 'paid' ? 'دراوە' : ($sale->status === 'partial' ? 'بەشێک دراوە' : 'نەدراوە') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-slate-400 py-10">
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-4xl mb-3 opacity-50">📭</span>
                                <span>هیچ فرۆشتنێک نییە بۆ پیشاندان</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>