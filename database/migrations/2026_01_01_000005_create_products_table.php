<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // بەرهەمەکان - Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('barcode')->unique()->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->string('unit')->default('دانە'); // یەکە: دانە، کیلۆ، بۆکس...
            $table->decimal('purchase_price', 15, 2)->default(0); // نرخی کڕین
            $table->decimal('sale_price', 15, 2)->default(0);     // نرخی فرۆشتن
            $table->integer('quantity')->default(0);              // کۆی بەردەست
            $table->integer('min_quantity')->default(5);          // کەمترین ئاست بۆ ئاگاداری
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
