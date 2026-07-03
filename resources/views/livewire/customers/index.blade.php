<div>
    <div class="flex items-center justify-between mb-5">
        <h1 class="text-xl font-bold text-gray-700">👥 شەریکەکان</h1>
        <button wire:click="openCreate" class="bg-[#141a2b] text-white px-4 py-2 rounded-lg text-sm">+ زیادکردنی شەریک</button>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr class="text-right">
                    <th class="py-3 px-4">ناو</th>
                    <th>مۆبایل</th>
                    <th>ئیمەیل</th>
                    <th>قەرز</th>
                    <th>کردار</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $c)
                <tr class="border-t">
                    <td class="py-3 px-4">{{ $c->name }}</td>
                    <td>{{ $c->phone }}</td>
                    <td>{{ $c->email }}</td>
                    <td class="{{ $c->balance > 0 ? 'text-red-500 font-semibold' : '' }}">{{ number_format($c->balance, 0) }} د.ع</td>
                    <td class="space-x-2 space-x-reverse">
                        <button wire:click="edit({{ $c->id }})" class="text-blue-600 text-xs">دەستکاری</button>
                        <button wire:click="delete({{ $c->id }})" wire:confirm="دڵنیایت؟" class="text-red-600 text-xs">سڕینەوە</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-gray-400 py-8">هیچ شەریکێک نییە</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $customers->links() }}</div>
    </div>

    @if($showModal)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" wire:click.self="$set('showModal', false)">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h2 class="font-bold text-lg mb-4">{{ $editingId ? 'دەستکاریکردن' : 'زیادکردنی شەریکی نوێ' }}</h2>
            <div class="space-y-3">
                <div>
                    <label class="text-xs text-gray-500">ناو</label>
                    <input wire:model="name" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-xs text-gray-500">مۆبایل</label>
                    <input wire:model="phone" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-xs text-gray-500">ئیمەیل</label>
                    <input wire:model="email" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-xs text-gray-500">ناونیشان</label>
                    <textarea wire:model="address" class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
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
