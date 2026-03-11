<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'product_code',
        'product_quantity',
        'product_category',
        'product_supplier',
        'buying_price',
        'selling_price',
        'product_image',
        'product_description',
    ];
}