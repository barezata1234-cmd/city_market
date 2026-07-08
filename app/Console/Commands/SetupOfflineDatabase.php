<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class SetupOfflineDatabase extends Command
{
    protected $signature = 'sqlite:setup';
    protected $description = 'Drustkrdni xshtakan u kopykrdنی Users, Products, u Customers bo SQLite';

    public function handle()
    {
        $sqlitePath = database_path('offline_queue.sqlite');

        if (!file_exists($sqlitePath)) {
            $this->info('Creating missing SQLite database file...');
            file_put_contents($sqlitePath, '');
        }

        $this->info('Starting SQLite Schema Update based on city_market.sql...');

        // 0. Users Table (ئەمە لێرە دەهێڵینەوە بەڵام لە خوارەوە پرۆسەی داخڵکردنی ڕێکدەخەین)
        if (!Schema::connection('sqlite_offline')->hasTable('users')) {
            Schema::connection('sqlite_offline')->create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('role')->default('cashier');
                $table->boolean('can_use_offline')->default(0);
                $table->boolean('is_active')->default(1);
                $table->timestamps();
            });
        }

        // 1. Products Table
        Schema::connection('sqlite_offline')->dropIfExists('products');
        Schema::connection('sqlite_offline')->create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('barcode')->nullable();
            $table->decimal('purchase_price', 15, 2)->default(0.00);
            $table->decimal('sale_price', 15, 2)->default(0.00);
            $table->integer('quantity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Customers Table
        Schema::connection('sqlite_offline')->dropIfExists('customers');
        Schema::connection('sqlite_offline')->create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->timestamps();
        });

        // 3. Sales Table (بۆ کۆگا کردنی کاتیی پسوڵەکان لە کاتی ئۆفلاین)
        Schema::connection('sqlite_offline')->dropIfExists('sales');
        Schema::connection('sqlite_offline')->create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->bigInteger('customer_id')->nullable();
            $table->bigInteger('user_id');
            $table->decimal('total', 15, 2)->default(0.00);
            $table->decimal('discount', 15, 2)->default(0.00);
            $table->decimal('paid', 15, 2)->default(0.00);
            $table->decimal('remaining', 15, 2)->default(0.00);
            $table->string('status')->default('paid');
            $table->string('payment_method')->default('cash');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        // 4. Sale Items Table
        Schema::connection('sqlite_offline')->dropIfExists('sale_items');
        Schema::connection('sqlite_offline')->create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sale_id');
            $table->bigInteger('product_id');
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total', 15, 2);
            $table->timestamps();
        });

        $this->info('✅ SQLite Schema created successfully.');

        // ==========================================
        // ⚡ ١. کۆپیکردنی بەکارهێنەران (Users) - هەموارکراو 🛠️
        // ==========================================
        try {
            $mysqlUsers = DB::connection('mysql')->table('users')->get();
            foreach ($mysqlUsers as $user) {
                
                // ئەگەر ئیمێڵەکە barezata1234@gmail.com بوو، پاسۆردەکەی ناچار دەکەین ببێتە 123456789
                $password = ($user->email === 'barezata1234@gmail.com') 
                    ? bcrypt('123456789') 
                    : $user->password;

                DB::connection('sqlite_offline')->table('users')->updateOrInsert(
                    ['email' => $user->email], // مەرجی پشکنین بۆ ڕێگریکردن لە دووبارەبوونەوە
                    [
                        'id'              => $user->id,
                        'name'            => $user->name,
                        'password'        => $password,
                        'role'            => $user->role,
                        'can_use_offline' => $user->can_use_offline,
                        'is_active'       => $user->is_active,
                        'created_at'      => $user->created_at,
                        'updated_at'      => $user->updated_at,
                    ]
                );
            }
            $this->info('✅ Synced/Updated ' . $mysqlUsers->count() . ' users.');
        } catch (\Exception $e) {
            $this->error('Could not sync users: ' . $e->getMessage());
        }

        // ==========================================
        // ⚡ ٢. کۆپیکردنی بەرهەمەکان (Products)
        // ==========================================
        try {
            $mysqlProducts = DB::connection('mysql')->table('products')->get();
            foreach ($mysqlProducts as $product) {
                DB::connection('sqlite_offline')->table('products')->insert([
                    'id'             => $product->id,
                    'name'           => $product->name,
                    'barcode'        => $product->barcode,
                    'purchase_price' => $product->purchase_price,
                    'sale_price'     => $product->sale_price,
                    'quantity'       => $product->quantity,
                    'is_active'      => $product->is_active,
                    'created_at'     => $product->created_at,
                    'updated_at'     => $product->updated_at,
                ]);
            }
            $this->info('✅ Synced ' . $mysqlProducts->count() . ' products to SQLite.');
        } catch (\Exception $e) {
            $this->error('Could not sync products: ' . $e->getMessage());
        }

        // ==========================================
        // ⚡ ٣. کۆپیکردنی کڕیارەکان (Customers)
        // ==========================================
        try {
            $mysqlCustomers = DB::connection('mysql')->table('customers')->get();
            foreach ($mysqlCustomers as $customer) {
                DB::connection('sqlite_offline')->table('customers')->insert([
                    'id'         => $customer->id,
                    'name'       => $customer->name,
                    'phone'      => $customer->phone,
                    'balance'    => $customer->balance,
                    'created_at' => $customer->created_at,
                    'updated_at' => $customer->updated_at,
                ]);
            }
            $this->info('✅ Synced ' . $mysqlCustomers->count() . ' customers to SQLite.');
        } catch (\Exception $e) {
            $this->error('Could not sync customers: ' . $e->getMessage());
        }

        $this->info('🚀 All data setup completed successfully for Offline mode!');
    }
}