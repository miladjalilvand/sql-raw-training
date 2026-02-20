<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'shipping_address',
        'payment_method'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // رابطه معکوس با user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // scope برای سفارشات فعال
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'processing']);
    }

    // accessor برای فرمت مبلغ
    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_amount, 2) . ' تومان';
    }
}
