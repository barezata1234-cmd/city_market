<?php

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
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\Login; // زیادکرا بۆ لایڤوایەر
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', fn () => redirect()->route('dashboard'));

// پشکنینی چالاکی سێرڤەر: فرۆنتێند بەکاریدەهێنێت بۆ زانینی ئایا ڕاستەقینە
// ئینتەرنێت/سێرڤەر بەردەستە یان نا (navigator.onLine بەس نییە بۆ ئەمە).
Route::get('/ping', fn () => response()->noContent());

// Guest routes (کاتێک بەکارهێنەر نەچووەتە ژوورەوە)
Route::middleware('guest')->group(function () {
    // بەکارهێنانی لایڤوایەر لە جیاتی کۆنتڕۆڵەری کۆن
    Route::get('/login', Login::class)->name('login');
    
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

// Authenticated routes (دوای چوونەژوورەوە)
Route::middleware('auth')->group(function () {
    
    // لۆجیکی لۆگاوت لێرە بە داخراوی دانرا تا پێویستت بە کۆنتڕۆڵەر نەمێنێت
    Route::post('/logout', function (\Illuminate\Http\Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');

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