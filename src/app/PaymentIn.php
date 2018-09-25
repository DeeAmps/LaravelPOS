<?php

namespace App;
use App\Currency;

use Illuminate\Database\Eloquent\Model;
use App\Sale;
class PaymentIn extends Model
{
    protected $table = 'in_payments';
    protected $fillable = ['quantity', 'currency_id', 'sale_id'];

    public function sale()
    {
        return $this->belongsTo('App\Sale');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }
}
