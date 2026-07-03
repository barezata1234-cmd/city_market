<?php

namespace App\Livewire\Purchases;

use App\Models\Purchase;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch() { $this->resetPage(); }

    public function render()
    {
        $purchases = Purchase::with('supplier', 'user')
            ->when($this->search, fn($q) => $q->where('invoice_number', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(12);

        return view('livewire.purchases.index', compact('purchases'))
            ->layout('layouts.app', ['title' => 'کڕینەکان']);
    }
}
