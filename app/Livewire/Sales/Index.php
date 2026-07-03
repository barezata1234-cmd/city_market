<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }

    public function render()
    {
        $sales = Sale::with('customer', 'user')
            ->when($this->search, fn($q) => $q->where('invoice_number', 'like', "%{$this->search}%"))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->latest()
            ->paginate(12);

        return view('livewire.sales.index', compact('sales'))
            ->layout('layouts.app', ['title' => 'فرۆشتن']);
    }
}
