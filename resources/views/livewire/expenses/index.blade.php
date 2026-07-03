<div>
    <div class="flex items-center justify-between mb-5">
        <h1 class="text-xl font-bold text-gray-700">💳 خەرجییەکان</h1>
        <button wire:click="openCreate" class="bg-[#141a2b] text-white px-4 py-2 rounded-lg text-sm">+ زیادکردنی خەرجی</button>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr class="text-right">
                    <th class="py-3 px-4">ناونیشان</th>
                    <th>بابەت</th>
                    <th>بڕ</th>
                    <th>بەروار</th>
                    <th>بەکارهێنەر</th>
                    <th>کردار</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $e)
                <tr class="border-t">
                    <td class="py-3 px-4">{{ $e->title }}</td>
                    <td>{{ $e->category }}</td>
                    <td class="text-red-500 font-semibold">{{ number_format($e->amount, 0) }} د.ع</td>
                    <td>{{ $e->expense_date->format('Y-m-d') }}</td>
                    <td>{{ $e->user->name ?? '-' }}</td>
                    <td class="space-x-2 space-x-reverse">
                        <button wire:click="edit({{ $e->id }})" class="text-blue-600 text-xs">دەستکاری</button>
                        <button wire:click="delete({{ $e->id }})" wire:confirm="دڵنیایت؟" class="text-red-600 text-xs">سڕینەوە</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-gray-400 py-8">هیچ خەرجییەک نییە</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $expenses->links() }}</div>
    </div>

    @if($showModal)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" wire:click.self="$set('showModal', false)">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h2 class="font-bold text-lg mb-4">{{ $editingId ? 'دەستکاریکردن' : 'زیادکردنی خەرجی' }}</h2>
            <div class="space-y-3">
                <div>
                    <label class="text-xs text-gray-500">ناونیشان</label>
                    <input wire:model="title" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-xs text-gray-500">بڕ</label>
                    <input type="number" step="0.01" wire:model="amount" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-xs text-gray-500">بابەت</label>
                    <input wire:model="category" placeholder="کرێ، کارەبا، مووچە..." class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-xs text-gray-500">بەروار</label>
                    <input type="date" wire:model="expense_date" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-xs text-gray-500">تێبینی</label>
                    <textarea wire:model="note" class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-5">
                <button wire:click="$set('showModal', false)" class="px-4 py-2 rounded-lg text-sm border">پاشگەزبوونەوە</button>
                <button wire:click="save" class="px-4 py-2 rounded-lg text-sm bg-[#141a2b] text-white">پاشەکەوتکردن</button>
            </div>
        </div>
    </div>
    @endif
</div>
