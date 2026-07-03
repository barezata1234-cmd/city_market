<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'phone', 'email', 'address', 'balance', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'balance' => 'decimal:2'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
