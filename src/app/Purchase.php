<?php

namespace App;
use App\PurchaseEntry;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchases';
    protected $guard = [];

    public function entries() 
    {
        return $this->hasMany('App\PurchaseEntry', 'purchase_id');
    }
}
