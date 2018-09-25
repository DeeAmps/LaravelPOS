<?php

namespace App;
use App\ProductCategory;
use App\StcokUnit;
use App\Manufacturer;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    // protected $fillable = ['name', 'description', 'manufacturer','product_categories_id'];
    protected $guarded = [];

    public function category() {
        return $this->belongsTo('App\ProductCategory', 'category_id', 'id');
    }

    public function barcodes() {
        return $this->hasMany('App\ProductType', 'id');
    }

    public function manufacturer() {
        return $this->belongsTo('App\Manufacturer');
    }


    public function stock_units() {
        return $this->belongsToMany('App\StockUnit', 'product_stock_units', 'product_id', 'stock_unit_id')
                        ->as('stock')->withPivot('stock_quantity', 'cost_price', 'selling_price', 'currency_id'); 
    }

    public function saleEntries() 
    {
        return $this->hasMany('App\SaleEntry');
    }
}
