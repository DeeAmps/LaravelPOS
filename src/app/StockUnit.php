<?php

namespace App;
use App\Product;

use Illuminate\Database\Eloquent\Model;

class StockUnit extends Model
{
    protected $table = 'stock_units';

    public function products()
    {
        return $this->belongsToMany('App\Product', 'product_stock_units', 'stock_unit_id', 'product_id')
                            ->as('stock')->withPivot('stock_quantity', 'cost_price', 'selling_price', 'currency_id');
    } 

    public function entries() {
        return $this->hasMany('App\SaleEntry');
    }
}
