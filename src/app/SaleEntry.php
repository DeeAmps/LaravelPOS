<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleEntry extends Model
{
    protected $table = 'sale_entries';
    protected $guard = [];

    public function sale() 
    {
        return $this->belongsTo('App\Sale', 'id');
    }

    public function product() {
        return $this->belongsTo('App\Product', 'product_id');
    }

    public function stock_unit() {
        return $this->belongsTo('App\StockUnit', 'stock_unit_id');
    }
}
