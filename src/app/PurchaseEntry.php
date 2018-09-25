<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseEntry extends Model
{
    protected $table = 'purchase_entries';
    protected $guard = [];

    public function purchase() 
    {
        return $this->belongsTo('App\Purchase', 'id');
    }
}
