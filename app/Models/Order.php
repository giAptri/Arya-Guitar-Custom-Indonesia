<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'customer_id',
        'guitar_id',
        'guitar_type',
        'pickup_config',
        'orientation',
        'notes',
        'estimated_price',
        'customer_name',
        'customer_phone',
        'reference_photo_path',
        'status',
    ];

    protected $casts = [
        'estimated_price' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            if (empty($order->order_code)) {
                $order->order_code = static::generateOrderCode();
            }
        });
    }

    protected static function generateOrderCode(): string
    {
        do {
            $code = 'ORD-' . now()->format('Y') . '-' . strtoupper(\Illuminate\Support\Str::random(6));
        } while (static::where('order_code', $code)->exists());

        return $code;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function guitar()
    {
        return $this->belongsTo(Guitar::class);
    }
}
