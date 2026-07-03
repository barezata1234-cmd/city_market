<div>
    <div class="flex items-center justify-between mb-5">
        <h1 class="text-xl font-bold text-gray-700">🧑‍💼 بەکارهێنەران</h1>
        <button wire:click="openCreate" class="bg-[#141a2b] text-white px-4 py-2 rounded-lg text-sm">+ زیادکردنی بەکارهێنەر</button>
    </div>

    @if (session('error'))
        <div class="mb-4 bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr class="text-right">
                    <th class="py-3 px-4">ناو</th>
                    <th>ئیمەیل</th>
                    <th>مۆبایل</th>
                    <th>ڕۆڵ</th>
                    <th>کردار</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr class="border-t">
                    <td class="py-3 px-4">{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->phone }}</td>
                    <td>
                        <span class="px-2 py-1 rounded text-xs bg-gray-100">
                            {{ ['admin' => 'بەڕێوەبەر', 'manager' => 'سەرپەرشتیار', 'cashier' => 'فرۆشیار'][$u->role] ?? $u->role }}
                        </span>
                    </td>
                    <td class="space-x-2 space-x-reverse">
                        <button wire:click="edit({{ $u->id }})" class="text-blue-600 text-xs">دەستکاری</button>
                        <button wire:click="delete({{ $u->id }})" wire:confirm="دڵنیایت؟" class="text-red-600 text-xs">سڕینەوە</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-gray-400 py-8">هیچ بەکارهێنەرێک نییە</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $users->links() }}</div>
    </div>

    @if($showModal)
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" wire:click.self="$set('showModal', false)">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h2 class="font-bold text-lg mb-4">{{ $editingId ? 'دەستکاریکردن' : 'زیادکردنی بەکارهێنەری نوێ' }}</h2>
            <div class="space-y-3">
                <div>
                    <label class="text-xs text-gray-500">ناو</label>
                    <input wire:model="name" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-xs text-gray-500">ئیمەیل</label>
                    <input wire:model="email" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-xs text-gray-500">مۆبایل</label>
                    <input wire:model="phone" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-xs text-gray-500">ڕۆڵ</label>
                    <select wire:model="role" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="admin">بەڕێوەبەر</option>
                        <option value="manager">سەرپەرشتیار</option>
                        <option value="cashier">فرۆشیار</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs text-gray-500">وشەی نهێنی {{ $editingId ? '(بەتاڵی بهێڵەرەوە ئەگەر نەتگۆڕی)' : '' }}</label>
                    <input type="password" wire:model="password" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
