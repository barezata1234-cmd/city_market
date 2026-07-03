<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'invoice_number', 'supplier_id', 'user_id', 'total', 'paid',
        'remaining', 'status', 'note',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'paid' => 'decimal:2',
        'remaining' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public static function generateInvoiceNumber(): string
    {
        return 'PUR-' . now()->format('Ymd') . '-' . str_pad((static::whereDate('created_at', today())->count() + 1), 4, '0', STR_PAD_LEFT);
    }
}
