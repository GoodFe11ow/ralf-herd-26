<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'total',
        'status',
        'payment_provider',
        'payment_reference',
    ];

    public function items() {
        return $this->hasMany(OrderItem::class);
    }
}
