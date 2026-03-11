<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_no',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'sub_total',
        'vat',
        'total',
        'payment_status',
        'order_status',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
}