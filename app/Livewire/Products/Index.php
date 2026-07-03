<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editingId = null;

    public $name, $barcode, $category_id, $supplier_id, $unit = 'دانە';
    public $purchase_price = 0, $sale_price = 0, $quantity = 0, $min_quantity = 5;

    protected $rules = [
        'name' => 'required|min:2',
        'barcode' => 'nullable|unique:products,barcode',
        'category_id' => 'nullable|exists:categories,id',
        'supplier_id' => 'nullable|exists:suppliers,id',
        'unit' => 'required',
        'purchase_price' => 'required|numeric|min:0',
        'sale_price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0',
        'min_quantity' => 'required|integer|min:0',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $p = Product::findOrFail($id);
        $this->editingId = $p->id;
        $this->name = $p->name;
        $this->barcode = $p->barcode;
        $this->category_id = $p->category_id;
        $this->supplier_id = $p->supplier_id;
        $this->unit = $p->unit;
        $this->purchase_price = $p->purchase_price;
        $this->sale_price = $p->sale_price;
        $this->quantity = $p->quantity;
        $this->min_quantity = $p->min_quantity;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->editingId) {
            $rules['barcode'] = 'nullable|unique:products,barcode,' . $this->editingId;
        }
        $data = $this->validate($rules);

        Product::updateOrCreate(['id' => $this->editingId], $data);

        session()->flash('success', $this->editingId ? 'بەرهەمەکە نوێکرایەوە' : 'بەرهەمی نوێ زیادکرا');
        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        session()->flash('success', 'بەرهەمەکە سڕایەوە');
    }

    public function resetForm()
    {
        $this->reset(['editingId', 'name', 'barcode', 'category_id', 'supplier_id', 'purchase_price', 'sale_price', 'quantity', 'min_quantity']);
        $this->unit = 'دانە';
        $this->resetValidation();
    }

    public function render()
    {
        $products = Product::with('category', 'supplier')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('barcode', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);

        $categories = Category::where('is_active', true)->get();
        $suppliers = Supplier::where('is_active', true)->get();

        return view('livewire.products.index', compact('products', 'categories', 'suppliers'))
            ->layout('layouts.app', ['title' => 'بەرهەمەکان']);
    }
}
