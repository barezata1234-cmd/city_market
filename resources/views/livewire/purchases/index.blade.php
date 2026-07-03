<div>
    <div class="flex items-center justify-between mb-5">
        <h1 class="text-xl font-bold text-gray-700">🚚 کڕینەکان</h1>
        <a href="{{ route('purchases.create') }}" class="bg-[#141a2b] text-white px-4 py-2 rounded-lg text-sm">+ کڕینی نوێ</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 mb-4">
        <input wire:model.live.debounce.400ms="search" placeholder="گەڕان بە ژمارەی وەصڵ..."
               class="border rounded-lg px-3 py-2 text-sm w-64">
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr class="text-right">
                    <th class="py-3 px-4">ژمارەی وەصڵ</th>
                    <th>دابینکەر</th>
                    <th>بەکارهێنەر</th>
                    <th>کۆی گشتی</th>
                    <th>ماوە</th>
                    <th>دۆخ</th>
                    <th>بەروار</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $purchase)
                <tr class="border-t">
                    <td class="py-3 px-4 font-medium">{{ $purchase->invoice_number }}</td>
                    <td>{{ $purchase->supplier->name ?? '-' }}</td>
                    <td>{{ $purchase->user->name }}</td>
                    <td>{{ number_format($purchase->total, 0) }} د.ع</td>
                    <td class="{{ $purchase->remaining > 0 ? 'text-red-500' : '' }}">{{ number_format($purchase->remaining, 0) }} د.ع</td>
                    <td>
                        <span class="px-2 py-1 rounded text-xs
                            {{ $purchase->status === 'paid' ? 'bg-green-100 text-green-700' : ($purchase->status === 'partial' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ $purchase->status === 'paid' ? 'دراوە' : ($purchase->status === 'partial' ? 'بەشێک دراوە' : 'نەدراوە') }}
                        </span>
                    </td>
                    <td class="text-gray-400">{{ $purchase->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-gray-400 py-8">هیچ کڕینێک نییە</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $purchases->links() }}</div>
    </div>
</div>
