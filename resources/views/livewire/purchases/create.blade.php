<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    @if (session('error'))
        <div class="lg:col-span-3 bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm">{{ session('error') }}</div>
    @endif

    {{-- Products list --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-4">
        <input wire:model.live.debounce.300ms="search" placeholder="گەڕان بۆ بەرهەم..."
               class="w-full border rounded-lg px-3 py-2 text-sm mb-4">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 max-h-[65vh] overflow-y-auto">
            @foreach($products as $p)
            <button wire:click="addToCart({{ $p->id }})" class="border rounded-lg p-3 text-right hover:border-[#141a2b] transition">
                <div class="font-semibold text-sm text-gray-700">{{ $p->name }}</div>
                <div class="text-xs text-gray-400 mt-1">نرخی کڕینی پێشوو: {{ number_format($p->purchase_price, 0) }} د.ع</div>
                <div class="text-xs text-gray-400">کۆگای ئێستا: {{ $p->quantity }}</div>
            </button>
            @endforeach
        </div>
    </div>

    {{-- Cart --}}
    <div class="bg-white rounded-xl shadow-sm p-4 flex flex-col">
        <div class="font-bold text-gray-700 mb-3">🧾 وەصڵی کڕین</div>
        <div class="flex-1 overflow-y-auto space-y-2 max-h-[45vh]">
            @forelse($cart as $productId => $item)
            <div class="border-b pb-2">
                <div class="text-sm font-medium mb-1">{{ $item['name'] }}</div>
                <div class="flex items-center gap-2">
                    <input type="number" min="0" step="0.01" wire:change="updatePrice({{ $productId }}, $event.target.value)"
                           value="{{ $item['price'] }}" class="w-20 border rounded px-1 py-1 text-xs text-center" title="نرخی کڕین">
                    <span class="text-xs text-gray-400">×</span>
                    <input type="number" min="1" wire:change="updateQty({{ $productId }}, $event.target.value)"
                           value="{{ $item['qty'] }}" class="w-14 border rounded px-1 py-1 text-xs text-center" title="بڕ">
                    <button wire:click="removeFromCart({{ $productId }})" class="text-red-500 text-xs mr-auto">✕</button>
                </div>
            </div>
            @empty
            <div class="text-center text-gray-400 text-sm py-10">سەبەتە بەتاڵە</div>
            @endforelse
        </div>

        <div class="mt-4 space-y-2 border-t pt-3">
            <select wire:model="supplier_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">دابینکەر هەڵبژێرە</option>
                @foreach($suppliers as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>

            <div class="flex items-center justify-between text-sm font-bold">
                <span>کۆی گشتی</span>
                <span>{{ number_format($this->total, 0) }} د.ع</span>
            </div>

            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">بڕی دراو</span>
                <input type="number" wire:model="paid" class="w-24 border rounded-lg px-2 py-1 text-sm text-left">
            </div>

            <button wire:click="checkout" class="w-full bg-[#141a2b] text-white rounded-lg py-2.5 text-sm font-semibold mt-2">
                ✅ تەواوکردنی کڕین
            </button>
        </div>
    </div>
</div>
