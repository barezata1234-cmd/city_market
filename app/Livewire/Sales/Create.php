<?php

namespace App\Livewire\Sales;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    public $search = '';
    public $cart = []; // [product_id => ['name'=>, 'price'=>, 'qty'=>, 'stock'=>]]
    public $customer_id = null;
    public $discount = 0;
    public $paid = 0;
    public $payment_method = 'cash';

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);

        if ($product->quantity <= 0) {
            session()->flash('error', 'بەرهەمەکە لە کۆگا نەماوە');
            return;
        }

        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['qty'] < $product->quantity) {
                $this->cart[$productId]['qty']++;
            }
        } else {
            $this->cart[$productId] = [
                'name' => $product->name,
                'price' => (float) $product->sale_price,
                'qty' => 1,
                'stock' => $product->quantity,
            ];
        }
    }
// ئەم مێتۆدە زیاد بکە بۆ ناو کلاسی Create لە هەر شوێنێک بێت
public function updatedSearch($value)
{
    // دڵنیابوونەوە لەوەی کە بەهای گەڕانەکە بەتاڵ نییە و درێژییەکەی گونجاوە بۆ بارکۆد
    // زۆربەی بارکۆدەکان ٨ یان ١٢ یان ١٣ ژمارەن، لێرەدا دانراوە ئەگەر لە ٦ زیاتر بوو
    if (!empty($value) && strlen($value) >= 6) {
        
        // گەڕان بەدوای بەرهەمەکە بەپێی بارکۆدی تەواو (Exact Match)
        $product = Product::where('is_active', true)
                          ->where('barcode', $value)
                          ->first();

        if ($product) {
            // ئەگەر بەرهەمەکە دۆزرایەوە، زیادی دەکات بۆ سەبەتەکە
            $this->addToCart($product->id);
            
            // پاککردنەوەی خانەی گەڕانەکە بۆ ئەوەی ئامادەبێت بۆ سکانی داهاتوو
            $this->search = ''; 
        }
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
            $this->cart[$productId]['qty'] = min($qty, $this->cart[$productId]['stock']);
        }
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
    }

    public function getTotalProperty()
    {
        $sum = collect($this->cart)->sum(fn($item) => $item['price'] * $item['qty']);
        return max(0, $sum - $this->discount);
    }

    public function checkout()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'سەبەتەکە بەتاڵە');
            return;
        }

        $this->validate([
            'discount' => 'numeric|min:0',
            'paid' => 'numeric|min:0',
            'payment_method' => 'required|in:cash,card,credit',
        ]);

        $total = $this->total;
        $remaining = max(0, $total - $this->paid);
        $status = $remaining <= 0 ? 'paid' : ($this->paid > 0 ? 'partial' : 'unpaid');

        DB::transaction(function () use ($total, $remaining, $status) {
            $sale = Sale::create([
                'invoice_number' => Sale::generateInvoiceNumber(),
                'customer_id' => $this->customer_id ?: null,
                'user_id' => Auth::id(),
                'total' => $total,
                'discount' => $this->discount,
                'paid' => $this->paid,
                'remaining' => $remaining,
                'status' => $status,
                'payment_method' => $this->payment_method,
            ]);

            foreach ($this->cart as $productId => $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productId,
                    'quantity' => $item['qty'],
                    'unit_price' => $item['price'],
                    'total' => $item['price'] * $item['qty'],
                ]);

                Product::where('id', $productId)->decrement('quantity', $item['qty']);
            }

            if ($this->customer_id && $remaining > 0) {
                Customer::where('id', $this->customer_id)->increment('balance', $remaining);
            }
        });

        session()->flash('success', 'فرۆشتنەکە بە سەرکەوتوویی تۆمارکرا');
        $this->reset(['cart', 'customer_id', 'discount', 'paid', 'search']);
        $this->payment_method = 'cash';
    }

    public function render()
    {
        $products = Product::where('is_active', true)
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('barcode', 'like', "%{$this->search}%"))
            ->limit(20)
            ->get();

        $customers = Customer::where('is_active', true)->get();

        return view('livewire.sales.create', compact('products', 'customers'))
            ->layout('layouts.app', ['title' => 'فرۆشتنی نوێ']);
    }
}
