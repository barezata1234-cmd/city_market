<?php

use App\Http\Controllers\Auth\LoginController;
use App\Livewire\Dashboard;
use App\Livewire\Categories\Index as CategoriesIndex;
use App\Livewire\Products\Index as ProductsIndex;
use App\Livewire\Suppliers\Index as SuppliersIndex;
use App\Livewire\Customers\Index as CustomersIndex;
use App\Livewire\Expenses\Index as ExpensesIndex;
use App\Livewire\Users\Index as UsersIndex;
use App\Livewire\Sales\Index as SalesIndex;
use App\Livewire\Sales\Create as SalesCreate;
use App\Livewire\Purchases\Index as PurchasesIndex;
use App\Livewire\Purchases\Create as PurchasesCreate;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::get('/products', ProductsIndex::class)->name('products.index');
    Route::get('/categories', CategoriesIndex::class)->name('categories.index');
    Route::get('/suppliers', SuppliersIndex::class)->name('suppliers.index');
    Route::get('/customers', CustomersIndex::class)->name('customers.index');
    Route::get('/expenses', ExpensesIndex::class)->name('expenses.index');
    Route::get('/users', UsersIndex::class)->name('users.index');

    Route::get('/sales', SalesIndex::class)->name('sales.index');
    Route::get('/sales/create', SalesCreate::class)->name('sales.create');

    Route::get('/purchases', PurchasesIndex::class)->name('purchases.index');
    Route::get('/purchases/create', PurchasesCreate::class)->name('purchases.create');
});
