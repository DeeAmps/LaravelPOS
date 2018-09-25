<?php

namespace App;

use Illuminate\Database\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class ProductCategory extends Model
{
    protected $table = 'categories';
    protected $fillable = [];

    public function products() {
        return $this->hasMany('App\Product', 'id');
    }

    public function numberOfProducts() {
        return $this->hasMany('App\Product', 'category_id')->count();
    }
}
