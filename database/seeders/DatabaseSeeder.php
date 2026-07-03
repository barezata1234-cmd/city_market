<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // بەکارهێنەری سەرەکی
        User::create([
            'name' => 'بەڕێوەبەری سیستەم',
            'email' => 'admin@marketplace.test',
            'phone' => '0750 000 0000',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // جۆرەکان
        $categories = ['خواردنەوە', 'خۆراک', 'خواردن', 'پاکژکردنەوە', 'کەلوپەلی ماڵ'];
        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }

        // دابینکەران
        $supplier = Supplier::create(['name' => 'کۆمپانیای گشتی دابینکردن', 'phone' => '0770 111 2222']);

        // بەرهەمەکان
        $products = [
            ['name' => 'ئاوی کانی 500ml', 'sale_price' => 500, 'purchase_price' => 350, 'quantity' => 200],
            ['name' => 'چای زۆڵبیا', 'sale_price' => 2000, 'purchase_price' => 1500, 'quantity' => 50],
            ['name' => 'برنجی مەسعود', 'sale_price' => 15000, 'purchase_price' => 12000, 'quantity' => 30],
            ['name' => 'شەکر 1kg', 'sale_price' => 1500, 'purchase_price' => 1200, 'quantity' => 100],
            ['name' => 'خۆراکی سیارە', 'sale_price' => 750, 'purchase_price' => 500, 'quantity' => 4],
        ];
        foreach ($products as $i => $p) {
            Product::create([
                'name' => $p['name'],
                'category_id' => $categories[$i % count($categories)] ? Category::inRandomOrder()->first()->id : null,
                'supplier_id' => $supplier->id,
                'unit' => 'دانە',
                'purchase_price' => $p['purchase_price'],
                'sale_price' => $p['sale_price'],
                'quantity' => $p['quantity'],
                'min_quantity' => 5,
            ]);
        }

        // کڕیارەکان
        Customer::create(['name' => 'کڕیاری ئاسایی', 'phone' => '0770 000 0000']);
    }
}
