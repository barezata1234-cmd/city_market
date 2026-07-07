<?php

use App\Http\Controllers\Api\SyncController;
use Illuminate\Support\Facades\Route;

// ئەم ڕێچکانە بۆ sync ـی offline بەکاردێن، بە token نەک بە session
Route::get('/products', [SyncController::class, 'products']);
Route::get('/customers', [SyncController::class, 'customers']);
Route::get('/offline-users', [SyncController::class, 'offlineUsers']);
Route::post('/sync-sales', [SyncController::class, 'syncSales']);
