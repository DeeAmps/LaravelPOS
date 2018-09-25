<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ProductCategory;
use App\Product;

class Manufacturer extends Model
{
    protected $table = 'manufacturers';

    public function products() 
    {
        return $this->hasMany('App\Product', 'id');
    }


    public function numberOfProducts() 
    {
        return $this->hasMany('App\Product', 'manufacturer_id')->count();
    }
}
