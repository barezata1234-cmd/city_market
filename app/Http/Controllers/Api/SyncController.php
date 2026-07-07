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
use Log;

class SyncController extends Controller
{
    /**
     * پشکنینی simple token بۆ ئەپڵیکەیشنی دێسکتۆپ
     */
    private function checkToken(Request $request): bool
    {
        $token = $request->header('X-Sync-Token');
        // بەکارهێنانی config() لەجیاتی env() ڕاستەوخۆ لە ناو کۆنتڕۆڵەردا
        return $token && hash_equals((string) config('app.sync_token', env('SYNC_TOKEN', '')), (string) $token);
    }

    /**
     * لیستی بەکارهێنەرانی مۆڵەتدراو بۆ چوونەژوورەوەی offline
     */
    public function offlineUsers(Request $request)
    {
        if (!$this->checkToken($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $users = User::where('can_use_offline', true)
            ->where('is_active', true)
            ->select('email', 'name', 'password')
            ->get();

        return response()->json($users);
    }

    /**
     * کاشی بەرهەمەکان
     */
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

    /**
     * کاشی کڕیارەکان
     */
    public function customers(Request $request)
    {
        if (!$this->checkToken($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $customers = Customer::where('is_active', true)->select('id', 'name')->get();

        return response()->json($customers);
    }

    /**
     * وەرگرتنی فرۆشتنە offline کراوەکان و تۆمارکردنیان
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
            'sales.*.discount' => 'numeric',
            'sales.*.paid' => 'numeric',
            'sales.*.payment_method' => 'in:cash,card,credit',
            'sales.*.cashier_email' => 'required|email',
            'sales.*.items' => 'required|array|min:1',
            'sales.*.items.*.product_id' => 'required|integer',
            'sales.*.items.*.qty' => 'required|integer|min:1',
            'sales.*.items.*.price' => 'required|numeric|min:0',
        ]);

        $results = [];

        foreach ($data['sales'] as $offlineSale) {
            // ١. چاککردنی کێشەی وەک یەک بوونی کلیلەکان لە بنکەی داتادا
            $existing = Sale::where('note', 'LIKE', 'offline_id:' . $offlineSale['local_id'] . '%')->first();
            if ($existing) {
                $results[] = [
                    'local_id' => $offlineSale['local_id'], 
                    'invoice_number' => $existing->invoice_number, 
                    'status' => 'already_synced'
                ];
                continue;
            }

            $user = User::where('email', $offlineSale['cashier_email'])->first();
            if (!$user) {
                $results[] = ['local_id' => $offlineSale['local_id'], 'error' => 'کاشێری نەدۆزرایەوە'];
                continue;
            }

            // لێرە خستنە ناو تڕانزاکشنەوە بۆ ئەوەی ئەگەر لە ناو لۆپەکەش کێشەیەک ڕوویدا داتاکان تێک نەچن
            try {
                // بەکارهێنانی throwException یان گەڕانەوەی ئەنجام بە شێوەی دروست
                $result = DB::transaction(function () use ($offlineSale, $user) {
                    $total = 0;
                    $validItems = [];
                    
                    foreach ($offlineSale['items'] as $item) {
                        $product = Product::find($item['product_id']);
                        if (!$product) continue;
                        
                        $qty = (int) $item['qty'];
                        $price = (float) $item['price'];
                        $total += $price * $qty;
                        $validItems[] = ['product' => $product, 'qty' => $qty, 'price' => $price];
                    }

                    if (empty($validItems)) {
                        throw new \Exception('هیچ بەرهەمێکی دروست نەبوو');
                    }

                    $total = max(0, $total - ($offlineSale['discount'] ?? 0));
                    $paid = (float) ($offlineSale['paid'] ?? 0);
                    $remaining = max(0, $total - $paid);
                    $status = $remaining <= 0 ? 'paid' : ($paid > 0 ? 'partial' : 'unpaid');

                    // ٢. چاککردنی کێشەی Race Condition لە دروستکردنی ژمارەی پسوولەدا (بەکارهێنانی لۆک بۆ هاوکاتی)
                    $todayCount = Sale::whereDate('created_at', today())->lockForUpdate()->count();
                    $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . str_pad($todayCount + 1, 4, '0', STR_PAD_LEFT);

                    $sale = Sale::create([
                        'invoice_number' => $invoiceNumber,
                        'customer_id' => $offlineSale['customer_id'] ?? null,
                        'user_id' => $user->id,
                        'total' => $total,
                        'discount' => $offlineSale['discount'] ?? 0,
                        'paid' => $paid,
                        'remaining' => $remaining,
                        'status' => $status,
                        'payment_method' => $offlineSale['payment_method'] ?? 'cash',
                        'note' => 'offline_id:' . $offlineSale['local_id'] . ' (sync کراوە لە کاتی نەبوونی ئینتەرنێت)',
                    ]);

                    foreach ($validItems as $vi) {
                        SaleItem::create([
                            'sale_id' => $sale->id,
                            'product_id' => $vi['product']->id,
                            'quantity' => $vi['qty'],
                            'unit_price' => $vi['price'],
                            'total' => $vi['price'] * $vi['qty'],
                        ]);
                        
                        // ٣. کەمکردنەوەی بڕی کۆگا بە شێوەی ڕاستەوخۆ لە سێرڤەر نەک لۆکاڵی بۆ دوورکەوتنەوە لە داتای هەڵە
                        $vi['product']->decrement('quantity', $vi['qty']);
                    }

                    return ['local_id' => $offlineSale['local_id'], 'invoice_number' => $sale->invoice_number, 'status' => 'synced'];
                });

                $results[] = $result;

            } catch (\Exception $e) {
                // لێرەدا تۆمارکردنی هەڵەکە لە لۆگی سێرڤەر بۆ ئەوەی بزانیت کێشەکە چی بووە
                Log::error('Sync error for local_id ' . $offlineSale['local_id'] . ': ' . $e->getMessage());
                $results[] = ['local_id' => $offlineSale['local_id'], 'error' => $e->getMessage()];
            }
        }

        return response()->json(['results' => $results]);
    }
}