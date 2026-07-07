<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SyncController extends Controller
{
    private function checkToken(Request $request): bool
    {
        $token = $request->header('X-Sync-Token');
        $secureToken = (string) env('SYNC_TOKEN', '');
        return !empty($secureToken) && $token && hash_equals($secureToken, (string) $token);
    }

    public function products(Request $request)
    {
        if (!$this->checkToken($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $products = Product::where('is_active', true)
            ->select('id', 'name', 'barcode', 'sale_price', 'quantity', 'unit')
            ->get();

        return response()->json($products);
    }

    public function customers(Request $request)
    {
        if (!$this->checkToken($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $customers = Customer::where('is_active', true)
            ->select('id', 'name')
            ->get();

        return response()->json($customers);
    }

    /**
     * هاوکاتکردنی فاکتۆرە ئۆفلاینەکان (پێشکەوتوو و پارێزراو)
     */
    public function syncSales(Request $request)
    {
        if (!$this->checkToken($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'sales' => 'required|array',
            'sales.*.local_id' => 'required|string',
            'sales.*.customer_id' => 'nullable|integer',
            'sales.*.discount' => 'numeric|min:0',
            'sales.*.paid' => 'numeric|min:0',
            'sales.*.payment_method' => 'in:cash,card,credit',
            'sales.*.cashier_email' => 'required|email',
            'sales.*.created_at' => 'nullable|date',
            'sales.*.items' => 'required|array|min:1',
            'sales.*.items.*.product_id' => 'required|integer',
            'sales.*.items.*.qty' => 'required|integer|min:1',
            'sales.*.items.*.price' => 'required|numeric|min:0',
        ]);

        $results = [];

        foreach ($data['sales'] as $offlineSale) {
            
            // 🛠️ چارەسەری کێشەی خاوڕێوەچوون: گۆڕینی LIKE بۆ کوێری ڕاستەوخۆ 
            // تێبینی: پێویستە ستونی local_id لە خشتەی sales هەبێت و Index بێت.
            $existing = Sale::where('local_id', $offlineSale['local_id'])->first();
            if ($existing) {
                $results[] = [
                    'local_id' => $offlineSale['local_id'], 
                    'invoice_number' => $existing->invoice_number, 
                    'status' => 'already_synced'
                ];
                continue;
            }

            // دۆزینەوەی کاشێر
            $user = User::where('email', $offlineSale['cashier_email'])->first();
            if (!$user) {
                $results[] = [
                    'local_id' => $offlineSale['local_id'], 
                    'error' => 'کاشێری دیاریکراو لە سێرڤەر بوونی نییە'
                ];
                continue;
            }

            try {
                // دەستپێکردنی Transaction بۆ هەر فاکتۆرێک بە جیا
                $syncResult = DB::transaction(function () use ($offlineSale, $user) {
                    
                    // 🛠️ چارەسەری Deadlock و N+1:
                    // ١. کۆکردنەوەی هەموو ئایدی بەرهەمەکان
                    $productIds = collect($offlineSale['items'])->pluck('product_id')->unique()->sort()->toArray();
                    
                    // ٢. قفڵکردنی هەموو بەرهەمەکان بەیەکەوە بەپێی ڕیزبەندی ئایدی
                    $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

                    $total = 0;
                    $validItems = [];

                    foreach ($offlineSale['items'] as $item) {
                        $product = $products->get($item['product_id']);
                        
                        if (!$product) continue;

                        $qty = (int) $item['qty'];
                        $price = (float) $item['price'];
                        $total += $price * $qty;

                        $validItems[] = [
                            'product' => $product,
                            'qty' => $qty,
                            'price' => $price
                        ];
                    }

                    if (empty($validItems)) {
                        throw new \Exception('هیچ بەرهەمێکی دروست یان مەوجود لەم فاکتۆرەدا نەدۆزرایەوە');
                    }

                    $discount = (float) ($offlineSale['discount'] ?? 0);
                    $total = max(0, $total - $discount);
                    $paid = (float) ($offlineSale['paid'] ?? 0);
                    $remaining = max(0, $total - $paid);
                    
                    $status = $remaining <= 0 ? 'paid' : ($paid > 0 ? 'partial' : 'unpaid');
                    $saleDate = isset($offlineSale['created_at']) ? now()->parse($offlineSale['created_at']) : now();
                    $invoiceNumber = 'INV-' . $saleDate->format('YmdHi') . '-' . strtoupper(Str::random(4));

                    // دروستکردنی فاکتۆر
                    $sale = Sale::create([
                        'invoice_number' => $invoiceNumber,
                        'local_id' => $offlineSale['local_id'], // پاشەکەوتکردنی ڕاستەوخۆ بۆ خشتەکە
                        'customer_id' => $offlineSale['customer_id'] ?? null,
                        'user_id' => $user->id,
                        'total' => $total,
                        'discount' => $discount,
                        'paid' => $paid,
                        'remaining' => $remaining,
                        'status' => $status,
                        'payment_method' => $offlineSale['payment_method'] ?? 'cash',
                        'note' => 'هاوکاتکراوە لە کاتی گەڕانەوەی ئینتەرنێت',
                        'created_at' => $saleDate,
                        'updated_at' => now(),
                    ]);

                    // پاشەکەوتکردنی ئایتمەکان و کەمکردنەوەی کۆگا
                    foreach ($validItems as $vi) {
                        SaleItem::create([
                            'sale_id' => $sale->id,
                            'product_id' => $vi['product']->id,
                            'quantity' => $vi['qty'],
                            'unit_price' => $vi['price'],
                            'total' => $vi['price'] * $vi['qty'],
                        ]);

                        // کەمکردنەوەی کۆگا
                        $vi['product']->decrement('quantity', $vi['qty']);
                    }

                    return [
                        'local_id' => $offlineSale['local_id'], 
                        'invoice_number' => $sale->invoice_number, 
                        'status' => 'synced'
                    ];
                });

                $results[] = $syncResult;

            } catch (\Exception $e) {
                $results[] = [
                    'local_id' => $offlineSale['local_id'], 
                    'error' => 'هەڵەیەک ڕوویدا لە کاتی جێبەجێکردندا: ' . $e->getMessage()
                ];
            }
        }

        return response()->json(['results' => $results]);
    }
}