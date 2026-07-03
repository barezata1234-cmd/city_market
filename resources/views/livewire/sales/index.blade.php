<div>
    <div class="flex items-center justify-between mb-5">
        <h1 class="text-xl font-bold text-gray-700">🛒 فرۆشتن</h1>
        <a href="{{ route('sales.create') }}" class="bg-[#141a2b] text-white px-4 py-2 rounded-lg text-sm">+ فرۆشتنی نوێ</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 mb-4 flex gap-3">
        <input wire:model.live.debounce.400ms="search" placeholder="گەڕان بە ژمارەی وەصڵ..."
               class="border rounded-lg px-3 py-2 text-sm w-64">
        <select wire:model.live="status" class="border rounded-lg px-3 py-2 text-sm">
            <option value="">هەموو دۆخەکان</option>
            <option value="paid">دراوە</option>
            <option value="partial">بەشێک دراوە</option>
            <option value="unpaid">نەدراوە</option>
        </select>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr class="text-right">
                    <th class="py-3 px-4">ژمارەی وەصڵ</th>
                    <th>کڕیار</th>
                    <th>فرۆشیار</th>
                    <th>کۆی گشتی</th>
                    <th>ماوە</th>
                    <th>دۆخ</th>
                    <th>بەروار</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                <tr class="border-t">
                    <td class="py-3 px-4 font-medium">{{ $sale->invoice_number }}</td>
                    <td>{{ $sale->customer->name ?? 'کڕیاری ئاسایی' }}</td>
                    <td>{{ $sale->user->name }}</td>
                    <td>{{ number_format($sale->total, 0) }} د.ع</td>
                    <td class="{{ $sale->remaining > 0 ? 'text-red-500' : '' }}">{{ number_format($sale->remaining, 0) }} د.ع</td>
                    <td>
                        <span class="px-2 py-1 rounded text-xs
                            {{ $sale->status === 'paid' ? 'bg-green-100 text-green-700' : ($sale->status === 'partial' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ $sale->status === 'paid' ? 'دراوە' : ($sale->status === 'partial' ? 'بەشێک دراوە' : 'نەدراوە') }}
                        </span>
                    </td>
                    <td class="text-gray-400">{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-gray-400 py-8">هیچ فرۆشتنێک نییە</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $sales->links() }}</div>
    </div>
</div>
