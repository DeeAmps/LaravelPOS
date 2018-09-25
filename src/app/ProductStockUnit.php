<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductStockUnit extends Model
{
    protected $table = 'product_stock_units';
    protected $fillabe = ['cost_price', 'selling_price', 'stock_quantity'];
}
