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
use Illuminate\Support\Facades\Hash;

class SyncController extends Controller
{
    /**
     * پشکنینی simple token بۆ ئەپڵیکەیشنی دێسکتۆپ (لەجیاتی session/login ئاسایی)
     */
    private function checkToken(Request $request): bool
    {
        $token = $request->header('X-Sync-Token');
        return $token && hash_equals((string) env('SYNC_TOKEN', ''), (string) $token);
    }

    /**
     * لیستی بەکارهێنەرانی مۆڵەتدراو بۆ چوونەژوورەوەی offline (تەنها ئەوانەی can_use_offline=true)
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
     * کاشی بەرهەمەکان — بۆ ئەوەی ئەپلیکەیشنی دێسکتۆپ لۆکاڵی هەڵبگرێت
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
     * Body: { "sales": [ { "local_id": "...", "customer_id": null, "discount": 0, "paid": 1000,
     *                      "payment_method": "cash", "created_at": "...", "cashier_email": "...",
     *                      "items": [ {"product_id": 1, "qty": 2, "price": 500}, ... ] }, ... ] }
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
            // ئەگەر پێشتر sync کرابوو (بەهۆی دووبارە هەوڵدانەوە)، جێبەجێی مەکەرەوە
            $existing = Sale::where('note', 'LIKE', '%offline_id:' . $offlineSale['local_id'] . '%')->first();
            if ($existing) {
                $results[] = ['local_id' => $offlineSale['local_id'], 'invoice_number' => $existing->invoice_number, 'status' => 'already_synced'];
                continue;
            }

            $user = User::where('email', $offlineSale['cashier_email'])->first();
            if (!$user) {
                $results[] = ['local_id' => $offlineSale['local_id'], 'error' => 'کاشێری نەدۆزرایەوە'];
                continue;
            }

            try {
                DB::transaction(function () use ($offlineSale, $user, &$results) {
                    $total = 0;
                    $validItems = [];
                    foreach ($offlineSale['items'] as $item) {
                        $product = Product::find($item['product_id']);
                        if (!$product) continue;
                        $qty = (int) $item['qty'];
                        $price = (float) $item['price'];
                        $total += $price * $qty;
                        $validItems[] = ['product_id' => $product->id, 'qty' => $qty, 'price' => $price];
                    }

                    if (empty($validItems)) {
                        $results[] = ['local_id' => $offlineSale['local_id'], 'error' => 'هیچ بەرهەمێکی دروست نەبوو'];
                        return;
                    }

                    $total = max(0, $total - ($offlineSale['discount'] ?? 0));
                    $paid = (float) ($offlineSale['paid'] ?? 0);
                    $remaining = max(0, $total - $paid);
                    $status = $remaining <= 0 ? 'paid' : ($paid > 0 ? 'partial' : 'unpaid');

                    $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . str_pad(
                        Sale::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT
                    );

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
                            'product_id' => $vi['product_id'],
                            'quantity' => $vi['qty'],
                            'unit_price' => $vi['price'],
                            'total' => $vi['price'] * $vi['qty'],
                        ]);
                        Product::where('id', $vi['product_id'])->decrement('quantity', $vi['qty']);
                    }

                    $results[] = ['local_id' => $offlineSale['local_id'], 'invoice_number' => $sale->invoice_number, 'status' => 'synced'];
                });
            } catch (\Exception $e) {
                $results[] = ['local_id' => $offlineSale['local_id'], 'error' => $e->getMessage()];
            }
        }

        return response()->json(['results' => $results]);
    }
}
