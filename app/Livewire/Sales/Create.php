<?php

namespace App\Livewire\Sales;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Services\NetworkService;
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

    public function mount()
    {
        // ئەگەر ئۆفلاین بووین، کەنێکشنی سەرەکی مۆدێلەکان دەگۆڕین بۆ SQLite
        if (!NetworkService::isOnline()) {
            config(['database.default' => 'sqlite_offline']);
        }
    }

    public function addToCart($productId)
    {
        // خوێندنەوەی بەرهەم لەو کەنێکشنەی کە ئێستا چالاکە (MySQL یان SQLite)
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

    public function updatedSearch($value)
    {
        if (!empty($value) && strlen($value) >= 6) {
            $product = Product::where('is_active', true)
                              ->where('barcode', $value)
                              ->first();

            if ($product) {
                $this->addToCart($product->id);
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

        // 🔌 پشکنینی دۆخی هێڵ بۆ دیاریکردنی جۆری پاشەکەوتکردن
        if (!NetworkService::isOnline()) {
            $this->saveToSQLite($total, $remaining, $status);
        } else {
            $this->saveToMySQL($total, $remaining, $status);
        }
    }

    // 🌐 میتۆدی پاشەکەوتکردنی سەرەکی لە ناو MySQL
    protected function saveToMySQL($total, $remaining, $status)
    {
        config(['database.default' => 'mysql']); // دڵنیابوونەوە لە گەڕانەوە بۆ MySQL

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
        $this->resetFields();
    }

    // 🔌 میتۆدی پاشەکەوتکردنی کاتی لە ناو SQLite (کاتی ئۆفلاین بوون)
    protected function saveToSQLite($total, $remaining, $status)
    {
        // گۆڕینی داینامیکی بۆ کەنێکشنی ئۆفلاین
        config(['database.default' => 'sqlite_offline']);

        DB::connection('sqlite_offline')->beginTransaction();
        try {
            // دروستکردنی ژمارەی پسوڵەی کاتیی تایبەت بە ئۆفلاین
            $invoiceNumber = 'OFF-' . date('YmdHis') . '-' . rand(100, 999);

            // تۆمارکردنی سەرپەڕی پسوڵە لە SQLite
            $saleId = DB::connection('sqlite_offline')->table('sales')->insertGetId([
                'invoice_number' => $invoiceNumber,
                'customer_id'    => $this->customer_id ?: null,
                'user_id'        => Auth::id() ?: 1,
                'total'          => $total,
                'discount'       => $this->discount,
                'paid'           => $this->paid,
                'remaining'      => $remaining,
                'status'         => $status,
                'payment_method' => $this->payment_method,
                'note'           => 'Offline Sale',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // تۆمارکردنی کاڵاکان لە خشتەی sale_items-ی SQLite
            foreach ($this->cart as $productId => $item) {
                DB::connection('sqlite_offline')->table('sale_items')->insert([
                    'sale_id'    => $saleId,
                    'product_id' => $productId,
                    'quantity'   => $item['qty'],
                    'unit_price' => $item['price'],
                    'total'      => $item['price'] * $item['qty'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // کەمکردنەوەی بڕی کاڵا لە ناو SQLite بۆ ڕێگریکردن لە فرۆشتنی زیاتر لە مەوجود
                DB::connection('sqlite_offline')->table('products')
                    ->where('id', $productId)
                    ->decrement('quantity', $item['qty']);
            }

            // ئەگەر کڕیارەکە قەرزدار بوو، لە ناو SQLite-دا بڕی قەرزەکەی زیاد دەکەین
            if ($this->customer_id && $remaining > 0) {
                DB::connection('sqlite_offline')->table('customers')
                    ->where('id', $this->customer_id)
                    ->increment('balance', $remaining);
            }

            DB::connection('sqlite_offline')->commit();

            session()->flash('success', 'پسوڵەکە لە دۆخی ئۆفلایندا پاشەکەوت کرا! (لە کاتی گەڕانەوەی هێڵ هاوکات دەکرێت)');
            $this->resetFields();

        } catch (\Exception $e) {
            DB::connection('sqlite_offline')->rollBack();
            session()->flash('error', 'کێشەیەک لە پاشەکەوتکردنی ئۆفلاین ڕوویدا: ' . $e->getMessage());
        }
    }

    private function resetFields()
    {
        $this->reset(['cart', 'customer_id', 'discount', 'paid', 'search']);
        $this->payment_method = 'cash';
    }

    public function render()
    {
        // لێرەدا ئەگەر ئۆفلاین بین خۆکار مۆدێلی Product و Customer لە SQLite دەخوێننەوە چونکە لە mount کەنێکشنەکە گۆڕدراوە
        $products = Product::where('is_active', true)
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('barcode', 'like', "%{$this->search}%"))
            ->limit(20)
            ->get();

        $customers = Customer::where('is_active', true)->get();

        return view('livewire.sales.create', compact('products', 'customers'))
            ->layout('layouts.app', ['title' => 'فرۆشتنی نوێ']);
    }
}