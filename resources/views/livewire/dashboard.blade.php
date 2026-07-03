<div>
    <div class="bg-gradient-to-l from-[#0e1830] to-[#141a2b] rounded-2xl px-8 py-6 mb-6 text-white flex items-center justify-between">
        <div>
            <div class="text-xl font-bold">بەخێرهاتیت، بەڕێوەبەر 👋</div>
            <div class="text-sm text-gray-300 mt-1">{{ now()->translatedFormat('l') }} | {{ now()->format('H:i Y/m/d') }}</div>
        </div>
        <div class="text-sm text-gray-400">ماركێتی شارەکەم</div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="stat-card bg-white p-4 shadow-sm">
            <div class="text-2xl mb-2">📦</div>
            <div class="text-xs text-gray-500">کۆی بەرهەمەکان</div>
            <div class="text-xl font-bold text-gray-800">{{ $stats['products_count'] }}</div>
        </div>
        <div class="stat-card bg-[#1a2540] p-4 text-white shadow-sm">
            <div class="text-2xl mb-2">💳</div>
            <div class="text-xs text-gray-300">خەرجی ئەمڕۆ</div>
            <div class="text-xl font-bold text-red-400">{{ number_format($stats['today_expenses'], 0) }} د.ع</div>
        </div>
        <div class="stat-card bg-[#3a2e14] p-4 text-white shadow-sm">
            <div class="text-2xl mb-2">🛒</div>
            <div class="text-xs text-gray-300">ژمارەی فرۆشتن</div>
            <div class="text-xl font-bold">{{ $stats['today_sales_count'] }}</div>
        </div>
        <div class="stat-card bg-[#0f3a2e] p-4 text-white shadow-sm">
            <div class="text-2xl mb-2">🔄</div>
            <div class="text-xs text-gray-300">قەرزی ئەمڕۆ</div>
            <div class="text-xl font-bold text-green-400">{{ number_format($stats['today_debt'], 0) }} د.ع</div>
        </div>
        <div class="stat-card bg-[#141a2b] p-4 text-white shadow-sm">
            <div class="text-2xl mb-2">🧾</div>
            <div class="text-xs text-gray-300">فرۆشتنی ئەمڕۆ</div>
            <div class="text-xl font-bold">{{ number_format($stats['today_sales_total'], 0) }} د.ع</div>
        </div>
        <div class="stat-card bg-white p-4 shadow-sm">
            <div class="text-2xl mb-2">⚠️</div>
            <div class="text-xs text-gray-500">کەمی کۆگا</div>
            <div class="text-xl font-bold text-orange-500">{{ $stats['low_stock'] }}</div>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('customers.index') }}" class="bg-[#141a2b] text-white rounded-xl p-5 text-center hover:opacity-90">
            <div class="text-2xl mb-2">📄</div>
            <div class="font-semibold">وەصڵەکان</div>
            <div class="text-xs text-gray-400 mt-1">بینین و چاپکردنی وەصڵ</div>
        </a>
        <a href="{{ route('categories.index') }}" class="bg-[#141a2b] text-white rounded-xl p-5 text-center hover:opacity-90">
            <div class="text-2xl mb-2">🏷️</div>
            <div class="font-semibold">جۆرەکان</div>
            <div class="text-xs text-gray-400 mt-1">بەڕێوەبردنی جۆرەکان</div>
        </a>
        <a href="{{ route('products.index') }}" class="bg-[#141a2b] text-white rounded-xl p-5 text-center hover:opacity-90">
            <div class="text-2xl mb-2">📦</div>
            <div class="font-semibold">بەرهەمەکان</div>
            <div class="text-xs text-gray-400 mt-1">بەڕێوەبردنی بەرهەمەکان</div>
        </a>
        <a href="{{ route('sales.create') }}" class="bg-[#141a2b] text-white rounded-xl p-5 text-center hover:opacity-90">
            <div class="text-2xl mb-2">🛒</div>
            <div class="font-semibold">فرۆشتن</div>
            <div class="text-xs text-gray-400 mt-1">فرۆشتن و وەصڵ دروستکردن</div>
        </a>
        <a href="{{ route('purchases.create') }}" class="bg-[#141a2b] text-white rounded-xl p-5 text-center hover:opacity-90">
            <div class="text-2xl mb-2">🧾</div>
            <div class="font-semibold">کڕین</div>
            <div class="text-xs text-gray-400 mt-1">کڕین لە دابینکەرەوە</div>
        </a>
    </div>

    <div class="mt-8 bg-white rounded-xl p-5 shadow-sm">
        <div class="font-semibold text-gray-700 mb-4">دوایین فرۆشتنەکان</div>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-400 text-right border-b">
                    <th class="py-2">ژمارەی وەصڵ</th>
                    <th>کڕیار</th>
                    <th>فرۆشیار</th>
                    <th>کۆی گشتی</th>
                    <th>دۆخ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentSales as $sale)
                <tr class="border-b last:border-0">
                    <td class="py-2">{{ $sale->invoice_number }}</td>
                    <td>{{ $sale->customer->name ?? 'کڕیاری ئاسایی' }}</td>
                    <td>{{ $sale->user->name }}</td>
                    <td>{{ number_format($sale->total, 0) }} د.ع</td>
                    <td>
                        <span class="px-2 py-1 rounded text-xs
                            {{ $sale->status === 'paid' ? 'bg-green-100 text-green-700' : ($sale->status === 'partial' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ $sale->status === 'paid' ? 'دراوە' : ($sale->status === 'partial' ? 'بەشێک دراوە' : 'نەدراوە') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-gray-400 py-6">هیچ فرۆشتنێک نییە</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
