<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_id',
        'name',
        'email',
        'phone',
        'address',
        'items',
        'subtotal',
        'shipping_cost',
        'total',
        'payment_type',
        'transaction_id',
        'transaction_status',
        'snap_token',
    ];

    protected $casts = [
        'items' => 'array',
    ];
}