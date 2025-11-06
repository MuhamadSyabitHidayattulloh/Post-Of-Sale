<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'cashier_id',
        'member_id',
        'subtotal',
        'discount',
        'tax',
        'total',
        'payment_method',
        'paid_amount',
        'change_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
