<div>
    <div class="flex items-center justify-between mb-5">
        <h1 class="text-xl font-bold text-gray-700">📦 بەرهەمەکان</h1>
        <button wire:click="openCreate" class="bg-[#141a2b] text-white px-4 py-2 rounded-lg text-sm">+ زیادکردنی بەرهەم</button>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 mb-4">
        <input wire:model.live.debounce.400ms="search" type="text" placeholder="گەڕان بە ناو یان بارکۆد..."
               class="w-full md:w-80 border rounded-lg px-3 py-2 text-sm">
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr class="text-right">
                    <th class="py-3 px-4">ناو</th>
                    <th>جۆر</th>
                    <th>نرخی کڕین</th>
                    <th>نرخی فرۆشتن</th>
                    <th>بڕ</th>
                    <th>دۆخ</th>
                    <th>کردار</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                <tr class="border-t">
                    <td class="py-3 px-4">{{ $p->name }}</td>
                    <td>{{ $p->category->name ?? '-' }}</td>
                    <td>{{ number_format($p->purchase_price, 0) }}</td>
                    <td>{{ number_format($p->sale_price, 0) }}</td>
                    <td>
                        <span class="{{ $p->isLowStock() ? 'text-red-500 font-bold' : '' }}">{{ $p->quantity }}</span>
                    </td>
                    <td>
                        @if($p->isLowStock())
                            <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs">کەمە</span>
                        @else
                            <span class="bg-green-100 text-green-600 px-2 py-1 rounded text-xs">باشە</span>
                        @endif
                    </td>
                    <td class="space-x-2 space-x-reverse">
                        <button wire:click="edit({{ $p->id }})" class="text-blue-600 text-xs">دەستکاری</button>
                        <button wire:click="delete({{ $p->id }})" wire:confirm="دڵنیایت لە سڕینەوە؟" class="text-red-600 text-xs">سڕینەوە</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-gray-400 py-8">هیچ بەرهەمێک نییە</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $products->links() }}</div>
    </div>

    @if($showModal)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" wire:click.self="$set('showModal', false)">
        <div class="bg-white rounded-xl p-6 w-full max-w-lg">
            <h2 class="font-bold text-lg mb-4">{{ $editingId ? 'دەستکاریکردنی بەرهەم' : 'زیادکردنی بەرهەمی نوێ' }}</h2>
            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="text-xs text-gray-500">ناوی بەرهەم</label>
                    <input wire:model="name" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-xs text-gray-500">بارکۆد</label>
                    <input wire:model="barcode" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @error('barcode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-xs text-gray-500">یەکە</label>
                    <input wire:model="unit" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-xs text-gray-500">جۆر</label>
                    <select wire:model="category_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">هەڵبژێرە</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs text-gray-500">دابینکەر</label>
                    <select wire:model="supplier_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">هەڵبژێرە</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs text-gray-500">نرخی کڕین</label>
                    <input type="number" step="0.01" wire:model="purchase_price" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-xs text-gray-500">نرخی فرۆشتن</label>
                    <input type="number" step="0.01" wire:model="sale_price" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-xs text-gray-500">بڕی بەردەست</label>
                    <input type="number" wire:model="quantity" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-xs text-gray-500">کەمترین ئاست</label>
                    <input type="number" wire:model="min_quantity" class="w-full border rounded-lg px-3 py-2 text-sm">
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
