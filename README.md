# ماركێتی شارەکەم — سیستەمی بەڕێوەبردنی فرۆشگا

سیستەمێکی تەواو بۆ بەڕێوەبردنی فرۆشگا/ماركێت، دروستکراوە بە **Laravel 11 + Livewire 3 + MySQL + TailwindCSS**، بە شێوازی هەمان داشبۆردی وێنەکە.

## ئەوەی تێدایە
- 📦 بەرهەمەکان (CRUD تەواو، جۆر، دابینکەر، نرخ، کۆگا)
- 🏷️ جۆرەکان
- 🚚 دابینکەران (لەگەڵ قەرز)
- 👥 شەریکەکان/کڕیارەکان (لەگەڵ قەرز)
- 🛒 فرۆشتن (سیستەمی POS: زیادکردنی بەرهەم بۆ سەبەتە، داشکاندن، پارەدان، قەرز)
- 🧾 کڕین لە دابینکەرەوە (نوێکردنەوەی ئۆتۆماتیکی کۆگا و نرخی کڕین)
- 💳 خەرجییەکان
- 🧑‍💼 بەکارهێنەران (ڕۆڵەکان: بەڕێوەبەر / سەرپەرشتیار / فرۆشیار)
- 🔐 چوونەژوورەوە
- 🏠 داشبۆرد بە ئامارە زیندووەکان (فرۆشتنی ئەمڕۆ، خەرجی، قەرز، کۆگای کەم)

## پێویستییەکان
- PHP >= 8.2
- Composer
- MySQL 8 (یان MariaDB)
- Node.js 18+ (بۆ Vite/Tailwind)

## هەنگاوەکانی دامەزراندن

### ١. دروستکردنی پرۆژەی Laravel + دانانی ئەم فایلانە
```bash
composer create-project laravel/laravel city-market
cd city-market
composer require livewire/livewire
```
دواتر هەموو فایلەکانی ئەم پاکێجە (app, database, resources, routes, .env.example, و هتد) بکۆپی بکە بۆ سەر پرۆژەکەت و شوێنی هاوشێوەکان بگۆڕەرەوە (overwrite).

> تێبینی: ئەم پاکێجە تەنها فایلە تایبەتەکانی ئەپڵیکەیشنەکەی تێدایە (app/, database/, resources/views, routes/web.php...)، نەک هەموو فرەیمۆرکی Laravel خۆی (vendor/, config/, و فایلە بنەڕەتییەکانی تر)، چونکە ئەوانە بە شێوەی ئۆتۆماتیکی لەگەڵ `composer create-project` دروست دەبن.

### ٢. ڕێکخستنی .env
```bash
cp .env.example .env
php artisan key:generate
```
دواتر لە فایلی `.env` زانیاری داتابەیسەکەت (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) دابنێ.

### ٣. دروستکردنی داتابەیس
لە MySQL:
```sql
CREATE DATABASE city_market CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### ٤. ڕاندنی migration و seeder
```bash
php artisan migrate --seed
```
ئەمە هەموو خشتەکان دروست دەکات و داتای نموونە (بەکارهێنەر، جۆر، بەرهەم) زیاد دەکات.

**هەژماری سەرەکی بۆ چوونەژوورەوە:**
- ئیمەیل: `admin@marketplace.test`
- وشەی نهێنی: `password`

### ٥. دامەزراندنی npm و بنیاتنان
```bash
npm install
npm run build
```
یان بۆ گەشەپێدان بە زیندووی:
```bash
npm run dev
```

### ٦. ڕاکردنی سێرڤەر
```bash
php artisan serve
```
دواتر بڕۆ بۆ: `http://127.0.0.1:8000`

## پێکهاتەی داتابەیس (خشتەکان)
| خشتە | باس |
|---|---|
| users | بەکارهێنەران (ڕۆڵ: admin/manager/cashier) |
| categories | جۆرەکانی بەرهەم |
| suppliers | دابینکەران (لەگەڵ بڕی قەرز) |
| customers | کڕیار/شەریک (لەگەڵ بڕی قەرز) |
| products | بەرهەمەکان (نرخی کڕین/فرۆشتن، کۆگا) |
| sales / sale_items | وەصڵی فرۆشتن و ئایتمەکانی |
| purchases / purchase_items | وەصڵی کڕین لە دابینکەرەوە |
| expenses | خەرجییەکان |
| sessions / cache | خشتەکانی سیستەم (Laravel) |

## پەرەپێدانی داهاتوو (پێشنیار)
- وەصڵی چاپکراو (PDF) بۆ فرۆشتن و کڕین
- ڕاپۆرتی مارکێت و ستۆک بە chart
- سیستەمی مۆڵەت/ڕۆڵ بە تەواوی (Policies/Gates) بۆ جیاکردنەوەی دەسەڵاتی admin/manager/cashier

---
دروستکراوە بۆ **ماركێتی شارەکەم** 🛍️
