<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Expense;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $today = today();

        $stats = [
            'products_count' => Product::count(),
            'today_sales_total' => Sale::whereDate('created_at', $today)->sum('total'),
            'today_sales_count' => Sale::whereDate('created_at', $today)->count(),
            'today_expenses' => Expense::whereDate('expense_date', $today)->sum('amount'),
            'today_debt' => Sale::whereDate('created_at', $today)->sum('remaining'),
            'low_stock' => Product::whereColumn('quantity', '<=', 'min_quantity')->count(),
        ];

        $recentSales = Sale::with('customer', 'user')->latest()->limit(5)->get();

        return view('livewire.dashboard', compact('stats', 'recentSales'))
            ->layout('layouts.app', ['title' => 'داشبۆرد']);
    }
}
