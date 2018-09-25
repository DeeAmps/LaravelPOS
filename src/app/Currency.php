<?php

namespace App;
use App\PaymentIn;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currencies';
    protected $fillable = ['label', 'symbol'];

    public function payments() 
    {
        return $this->hasMany('App\PaymentIn');
    }
}
