<?php

namespace App\Livewire\Purchases;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    public $search = '';
    public $cart = []; // [product_id => ['name'=>, 'price'=>, 'qty'=>]]
    public $supplier_id = null;
    public $paid = 0;

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['qty']++;
        } else {
            $this->cart[$productId] = [
                'name' => $product->name,
                'price' => (float) $product->purchase_price,
                'qty' => 1,
            ];
        }
    }

    public function updateQty($productId, $qty)
    {
        $qty = (int) $qty;
        if ($qty <= 0) {
            unset($this->cart[$productId]);
            return;
        }
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['qty'] = $qty;
        }
    }

    public function updatePrice($productId, $price)
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['price'] = max(0, (float) $price);
        }
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
    }

    public function getTotalProperty()
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['qty']);
    }

    public function checkout()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'سەبەتەکە بەتاڵە');
            return;
        }
        if (!$this->supplier_id) {
            session()->flash('error', 'تکایە دابینکەر هەڵبژێرە');
            return;
        }

        $this->validate(['paid' => 'numeric|min:0']);

        $total = $this->total;
        $remaining = max(0, $total - $this->paid);
        $status = $remaining <= 0 ? 'paid' : ($this->paid > 0 ? 'partial' : 'unpaid');

        DB::transaction(function () use ($total, $remaining, $status) {
            $purchase = Purchase::create([
                'invoice_number' => Purchase::generateInvoiceNumber(),
                'supplier_id' => $this->supplier_id,
                'user_id' => Auth::id(),
                'total' => $total,
                'paid' => $this->paid,
                'remaining' => $remaining,
                'status' => $status,
            ]);

            foreach ($this->cart as $productId => $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $productId,
                    'quantity' => $item['qty'],
                    'unit_price' => $item['price'],
                    'total' => $item['price'] * $item['qty'],
                ]);

                // زیادکردنی کۆگا و نوێکردنەوەی نرخی کڕین
                Product::where('id', $productId)->update([
                    'purchase_price' => $item['price'],
                ]);
                Product::where('id', $productId)->increment('quantity', $item['qty']);
            }

            if ($remaining > 0) {
                Supplier::where('id', $this->supplier_id)->increment('balance', $remaining);
            }
        });

        session()->flash('success', 'کڕینەکە بە سەرکەوتوویی تۆمارکرا و کۆگا نوێکرایەوە');
        $this->reset(['cart', 'supplier_id', 'paid', 'search']);
    }

    public function render()
    {
        $products = Product::where('is_active', true)
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('barcode', 'like', "%{$this->search}%"))
            ->limit(20)
            ->get();

        $suppliers = Supplier::where('is_active', true)->get();

        return view('livewire.purchases.create', compact('products', 'suppliers'))
            ->layout('layouts.app', ['title' => 'کڕینی نوێ']);
    }
}
